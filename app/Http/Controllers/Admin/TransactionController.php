<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            // Buat transaksi baru
            $transaction = Transaction::create([
                'transaction_date' => Carbon::now(),
                'total_price'      => 0,
                'user_id'          => Auth::id(),
                'payment_method'   => $request->input('payment_method', 'Tunai / COD'),
            ]);

            $totalPrice = 0;

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
            }

            // hitungTotal() : decimal
            $transaction->update(['total_price' => $totalPrice]);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transaksi berhasil disimpan. Total: Rp ' . number_format($totalPrice, 0, ',', '.')
                ]);
            }

            return redirect()->route('admin.pesanan')
                ->with('success', 'Transaksi berhasil disimpan. Total: Rp ' . number_format($totalPrice, 0, ',', '.'));
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
            }

            return back()->with('error', $e->getMessage())->withInput();
        }
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
                'metode'  => self::formatPaymentMethod($tr->payment_method)
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
