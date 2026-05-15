<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProduk       = Product::count();
        $totalUser         = User::count();
        $transaksiHariIni  = Transaction::whereDate('transaction_date', Carbon::today())->where('payment_status', 'paid')->count();
        $pendapatanHariIni = Transaction::whereDate('transaction_date', Carbon::today())->where('payment_status', 'paid')->sum('total_price');
        $transaksiTerbaru  = Transaction::with('user')->orderBy('created_at', 'desc')->limit(5)->get();

        // Statistik penjualan (semua waktu, hanya transaksi lunas)
        $paidTransactions   = Transaction::where('payment_status', 'paid');
        $totalRevenue       = (clone $paidTransactions)->sum('total_price');
        $totalPaidCount     = (clone $paidTransactions)->count();
        $avgPerTransaction  = $totalPaidCount > 0 ? round($totalRevenue / $totalPaidCount) : 0;
        $pendingCount       = Transaction::where('payment_status', 'pending')->count();
        $failedCount        = Transaction::where('payment_status', 'failed')->count();

        // Metode terpopuler
        $topMethod = Transaction::where('payment_status', 'paid')
            ->selectRaw('payment_method, COUNT(*) as cnt')
            ->groupBy('payment_method')
            ->orderByDesc('cnt')
            ->first();
        $topMethodLabel = $topMethod ? self::formatPaymentMethod($topMethod->payment_method) : '-';

        // Revenue per metode
        $revenueByMethod = Transaction::where('payment_status', 'paid')
            ->selectRaw('payment_method, SUM(total_price) as total, COUNT(*) as cnt')
            ->groupBy('payment_method')
            ->get()
            ->keyBy('payment_method');

        return view('admin.dashboard', compact(
            'totalProduk',
            'totalUser',
            'transaksiHariIni',
            'pendapatanHariIni',
            'transaksiTerbaru',
            'totalRevenue',
            'totalPaidCount',
            'avgPerTransaction',
            'pendingCount',
            'failedCount',
            'topMethodLabel',
            'revenueByMethod'
        ));
    }

    private static function formatPaymentMethod($method)
    {
        $map = [
            'transfer' => 'Transfer',
            'cod'      => 'Tunai / COD',
            'qris'     => 'QRIS',
        ];
        return $map[$method] ?? ($method ?: 'Tunai / COD');
    }
}
