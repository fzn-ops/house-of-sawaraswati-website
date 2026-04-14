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
        $transactions = Transaction::with(['user', 'transactionDetails.product'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.pesanan', compact('transactions'));
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

            return redirect()->route('admin.pesanan')
                ->with('success', 'Transaksi berhasil disimpan. Total: Rp ' . number_format($totalPrice, 0, ',', '.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    // getTransaksi() : Transaction (detail satu transaksi)
    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'transactionDetails.product']);
        return response()->json($transaction);
    }

    // getLaporanHarian() : list
    public function laporanHarian(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());

        $transactions = Transaction::with(['user', 'transactionDetails.product'])
            ->whereDate('transaction_date', $date)
            ->orderBy('transaction_date', 'desc')
            ->get();

        $totalHarian = $transactions->sum('total_price');

        return view('admin.penjualan', compact('transactions', 'totalHarian', 'date'));
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
}
