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
        $transaksiHariIni  = Transaction::whereDate('transaction_date', Carbon::today())->count();
        $pendapatanHariIni = Transaction::whereDate('transaction_date', Carbon::today())->sum('total_price');
        $transaksiTerbaru  = Transaction::with('user')->orderBy('created_at', 'desc')->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalProduk',
            'totalUser',
            'transaksiHariIni',
            'pendapatanHariIni',
            'transaksiTerbaru'
        ));
    }
}
