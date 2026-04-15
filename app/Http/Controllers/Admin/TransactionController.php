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
        $products = Product::where('stok', '>', 0)->get();
        return view('admin.pesanan', compact('products'));
    }

    // buatTransaksi() : void (form kasir)
    public function create()
    {
        $products = Product::where('stok', '>', 0)->get();
        return view('admin.transaksi.create', compact('products'));
    }

    // simpanTransaksi() : void + hitungTotal() : decimal
    public function store(Request $request)
    {
        $request->validate([
            'payment_method'     => 'nullable|string',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,product_id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Generate unique order_id untuk Midtrans
            $orderId = 'HOS-' . time() . '-' . rand(1000, 9999);

            // Buat transaksi baru
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
                $product = Product::findOrFail($item['product_id']);

                // Cek stok cukup
                if ($product->stok < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi.");
                }

                // hitungSubtotal() : decimal
                $subtotal = $product->price * $item['quantity'];
                $totalPrice += $subtotal;

                // tambahItem() : void
                TransactionDetail::create([
                    'transaction_id' => $transaction->transaction_id,
                    'product_id'     => $product->product_id,
                    'quantity'       => $item['quantity'],
                    'price'          => $product->price,
                    'subtotal'       => $subtotal,
                ]);

                // updateStok() : void — kurangi stok produk
                $product->decrement('stok', $item['quantity']);

                // Siapkan item detail untuk Midtrans
                $itemDetails[] = [
                    'id'       => (string) $product->product_id,
                    'price'    => (int) $product->price,
                    'quantity' => (int) $item['quantity'],
                    'name'     => substr($product->name, 0, 50), // Midtrans limit 50 chars
                ];
            }

            // hitungTotal() : decimal
            $transaction->update(['total_price' => $totalPrice]);

            // ===== MIDTRANS SNAP TOKEN =====
            $snapToken = null;
            $paymentMethod = $request->input('payment_method', 'cod');

            // Hanya generate snap token jika metode pembayaran bukan COD/Tunai
            if ($paymentMethod !== 'cod') {
                $snapToken = $this->generateSnapToken($transaction, $itemDetails, $totalPrice);
                $transaction->update(['snap_token' => $snapToken]);
            } else {
                // Pembayaran tunai langsung paid
                $transaction->update(['payment_status' => 'paid']);
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
     * Generate Midtrans Snap Token
     */
    private function generateSnapToken(Transaction $transaction, array $itemDetails, int $totalPrice): ?string
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

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            Log::info("Snap Token generated for order: {$transaction->order_id}");
            return $snapToken;
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token Error: ' . $e->getMessage());
            throw new \Exception('Gagal membuat token pembayaran Midtrans: ' . $e->getMessage());
        }
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

        $transaction->update(['payment_status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran berhasil diperbarui',
        ]);
    }

    // getTransaksi() : Transaction (detail satu transaksi)
    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'transactionDetails.product']);
        return response()->json($transaction);
    }

    public function laporanHarian(Request $request)
    {
        $query = Transaction::with(['user', 'transactionDetails.product'])
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
                        'ukuran' => 'All Size',
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

        $transactions = Transaction::with(['user', 'transactionDetails.product'])
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
            'ewallet'  => 'E-Wallet',
        ];
        return $map[$method] ?? ($method ?: 'Tunai / COD');
    }
}
