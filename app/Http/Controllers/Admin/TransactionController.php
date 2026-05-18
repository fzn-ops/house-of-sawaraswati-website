<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TransactionController extends Controller
{
    // getTransaksi() : Transaction (tampilkan semua transaksi)
    public function index()
    {
        $products = Product::with('sizes')->where('stok', '>', 0)->get();
        return view('admin.pesanan', compact('products'));
    }

    // buatTransaksi() : void (form kasir)
    public function create()
    {
        $products = Product::with('sizes')->where('stok', '>', 0)->get();
        return view('admin.transaksi.create', compact('products'));
    }

    // simpanTransaksi() : void + hitungTotal() : decimal
    public function store(Request $request)
    {
        $request->validate([
            'payment_method'     => 'nullable|string',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,product_id',
            'items.*.size'       => 'nullable|string|max:50',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $orderId = 'HOS-' . time() . '-' . rand(1000, 9999);

            $transaction = Transaction::create([
                'order_id'         => $orderId,
                'transaction_date' => Carbon::now(),
                'total_price'      => 0,
                'user_id'          => Auth::id(),
                'payment_method'   => $request->input('payment_method', 'Tunai / COD'),
                'payment_status'   => 'pending',
            ]);

            $totalPrice = 0;
            $itemDetails = [];

            foreach ($request->items as $item) {
                $product = Product::with('sizes')->findOrFail($item['product_id']);
                $size    = $item['size'] ?? null;
                $qty     = (int) $item['quantity'];

                $sizeRow = $size
                    ? $product->sizes->firstWhere('size', $size)
                    : null;

                if ($sizeRow) {
                    if ($sizeRow->stok < $qty) {
                        throw new \Exception("Stok {$product->name} ({$size}) tidak mencukupi.");
                    }
                } else {
                    if ($product->stok < $qty) {
                        throw new \Exception("Stok {$product->name} tidak mencukupi.");
                    }
                }

                $subtotal = $product->price * $qty;
                $totalPrice += $subtotal;

                TransactionDetail::create([
                    'transaction_id' => $transaction->transaction_id,
                    'product_id'     => $product->product_id,
                    'size'           => $size,
                    'quantity'       => $qty,
                    'price'          => $product->price,
                    'subtotal'       => $subtotal,
                ]);

                $itemName = $size ? "{$product->name} ({$size})" : $product->name;
                $itemDetails[] = [
                    'id'       => (string) $product->product_id,
                    'price'    => (int) $product->price,
                    'quantity' => $qty,
                    'name'     => substr($itemName, 0, 50),
                ];
            }

            $transaction->update(['total_price' => $totalPrice]);

            // ===== MIDTRANS SNAP TOKEN =====
            $snapToken = null;
            $paymentMethod = $request->input('payment_method', 'cod');

            // Hanya generate snap token jika metode pembayaran bukan COD/Tunai
            if ($paymentMethod !== 'cod') {
                $snapToken = $this->generateSnapToken($transaction, $itemDetails, $totalPrice, $paymentMethod);
                $transaction->update(['snap_token' => $snapToken]);
            } else {
                // Pembayaran tunai langsung paid — potong stok sekarang
                $transaction->update(['payment_status' => 'paid']);
                $this->decrementStock($transaction);
            }

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success'    => true,
                    'message'    => 'Transaksi berhasil disimpan. Total: Rp ' . number_format($totalPrice, 0, ',', '.'),
                    'snap_token' => $snapToken,
                    'order_id'   => $orderId,
                    'is_cash'    => $paymentMethod === 'cod',
                ]);
            }

            return redirect()->route('admin.pesanan')
                ->with('success', 'Transaksi berhasil disimpan. Total: Rp ' . number_format($totalPrice, 0, ',', '.'));
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Transaction Error: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
            }

            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Generate Midtrans Snap Token dengan filter metode pembayaran spesifik.
     */
    private function generateSnapToken(Transaction $transaction, array $itemDetails, int $totalPrice, string $paymentMethod = ''): ?string
    {
        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production', false);
        \Midtrans\Config::$isSanitized  = config('midtrans.is_sanitized', true);
        \Midtrans\Config::$is3ds        = config('midtrans.is_3ds', true);

        $params = [
            'transaction_details' => [
                'order_id'     => $transaction->order_id,
                'gross_amount' => (int) $totalPrice,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => Auth::user()->name ?? 'Customer',
                'email'      => Auth::user()->email ?? 'customer@houseofsaraswati.com',
            ],
        ];

        // Filter enabled_payments berdasarkan metode yang dipilih di POS
        $enabledPayments = $this->getEnabledPayments($paymentMethod);
        if (!empty($enabledPayments)) {
            $params['enabled_payments'] = $enabledPayments;
        }

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            Log::info("Snap Token generated for order: {$transaction->order_id}, method: {$paymentMethod}");
            return $snapToken;
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token Error: ' . $e->getMessage());
            throw new \Exception('Gagal membuat token pembayaran Midtrans: ' . $e->getMessage());
        }
    }

    /**
     * Mapping metode pembayaran POS ke enabled_payments Midtrans.
     * Setiap metode hanya mengaktifkan channel pembayaran yang relevan.
     *
     * @see https://docs.midtrans.com/reference/enabled-payments
     */
    private function getEnabledPayments(string $method): array
    {
        $map = [
            'transfer' => [
                'bank_transfer',
                'bca_va', 'bni_va', 'bri_va', 'permata_va',
                'echannel',       // Mandiri Bill Payment
            ],
            'qris' => [
                'other_qris',     // QRIS generic
            ],
        ];

        return $map[$method] ?? [];
    }

    /**
     * Update payment status dari frontend setelah pembayaran Midtrans
     */
    public function updatePaymentStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'status'   => 'required|string|in:paid,pending,failed',
        ]);

        $transaction = Transaction::where('order_id', $request->order_id)->first();

        if (!$transaction) {
            return response()->json(['success' => false, 'error' => 'Transaksi tidak ditemukan'], 404);
        }

        $oldStatus = $transaction->payment_status;
        $newStatus = $request->status;

        // Validasi transisi status yang diperbolehkan
        $allowedTransitions = [
            'pending' => ['paid', 'failed'],
            'failed'  => ['pending'],
            'paid'    => [],
        ];

        $allowed = $allowedTransitions[$oldStatus] ?? [];
        if (!in_array($newStatus, $allowed)) {
            return response()->json([
                'success' => false,
                'error'   => "Tidak bisa ubah status dari '{$oldStatus}' ke '{$newStatus}'",
            ], 422);
        }

        $transaction->update(['payment_status' => $newStatus]);

        // Potong stok saat status berubah ke paid
        if ($newStatus === 'paid' && $oldStatus !== 'paid') {
            $this->decrementStock($transaction);
        }

        // Audit trail
        Log::info("Payment status updated manually", [
            'order_id'   => $transaction->order_id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => Auth::id(),
            'changed_at' => now()->toDateTimeString(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Status berhasil diubah dari '{$oldStatus}' ke '{$newStatus}'",
        ]);
    }

    // getTransaksi() : Transaction (detail satu transaksi)
    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'transactionDetails.product' => fn($q) => $q->withTrashed()]);
        return response()->json($transaction);
    }

    public function laporanHarian(Request $request)
    {
        $query = Transaction::with(['user', 'transactionDetails.product' => fn($q) => $q->withTrashed()])
            ->orderBy('transaction_date', 'desc');

        if ($request->has('date') && $request->get('date') != '') {
            $query->whereDate('transaction_date', $request->get('date'));
        }

        $transactions = $query->get();

        // Format data untuk JSON Javascript frontend
        $mappedData = $transactions->map(function ($tr) {
            return [
                'id'      => 'TRX-' . $tr->transaction_id,
                'tanggal' => Carbon::parse($tr->transaction_date)->translatedFormat('d F Y H:i'),
                'produk'  => $tr->transactionDetails->map(function ($detail) {
                    return [
                        'nama'   => $detail->product ? $detail->product->name : 'Produk Terhapus',
                        'ukuran' => $detail->size ?? 'All Size',
                        'qty'    => $detail->quantity
                    ];
                })->toArray(),
                'total'   => $tr->total_price,
                'metode'  => self::formatPaymentMethod($tr->payment_method),
                'status'  => $tr->payment_status ?? 'paid',
            ];
        });

        $totalHarian = $transactions->sum('total_price');
        $date = $request->get('date', '');

        return view('admin.penjualan', compact('mappedData', 'totalHarian', 'date'));
    }

    // getLaporanBulanan() : list
    public function laporanBulanan(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year  = $request->get('year', Carbon::now()->year);

        $transactions = Transaction::with(['user', 'transactionDetails.product' => fn($q) => $q->withTrashed()])
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->orderBy('transaction_date', 'desc')
            ->get();

        $totalBulanan = $transactions->sum('total_price');

        return response()->json([
            'transactions'  => $transactions,
            'total_bulanan' => $totalBulanan,
            'bulan'         => $month,
            'tahun'         => $year,
        ]);
    }

    private static function formatPaymentMethod($method) {
        $map = [
            'transfer' => 'Transfer',
            'cod'      => 'Tunai / COD',
            'qris'     => 'QRIS',
        ];
        return $map[$method] ?? ($method ?: 'Tunai / COD');
    }

    private function decrementStock(Transaction $transaction)
    {
        $transaction->load('transactionDetails.product.sizes');

        foreach ($transaction->transactionDetails as $detail) {
            if (!$detail->product) {
                continue;
            }

            if ($detail->size) {
                $sizeRow = $detail->product->sizes->firstWhere('size', $detail->size);
                if ($sizeRow) {
                    $sizeRow->decrement('stok', $detail->quantity);
                }
            }

            $detail->product->decrement('stok', $detail->quantity);
        }
    }
}
