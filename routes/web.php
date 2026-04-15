<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\CompanyProfileController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\MidtransController;
use App\Http\Middleware\AdminMiddleware;

// Katalog
Route::get('/katalog', [FrontendController::class, 'katalog'])->name('katalog');
Route::get('/katalog/{product}', [FrontendController::class, 'show'])->name('katalog.show');

// Beranda / Landing Page
Route::get('/', [FrontendController::class, 'home'])->name('home');

// Kontak
Route::get('/kontak', [FrontendController::class, 'contact'])->name('contact');

// ===== MIDTRANS NOTIFICATION (Public - No Auth) =====
Route::post('/midtrans/notification', [MidtransController::class, 'notification'])->name('midtrans.notification');

// ===== ADMIN ROUTES =====
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/',        [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login',  [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
 
    // Protected routes (perlu login)
    Route::middleware('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // User Management (CRUD) - Hanya Admin
        Route::resource('users', UserController::class)->except(['show'])->middleware('admin.role');

        // Produk Management
        Route::get('/produk',               [ProdukController::class, 'index'])->name('produk');
        Route::post('/produk',              [ProdukController::class, 'store'])->name('produk.store');
        Route::get('/produk/{product}',     [ProdukController::class, 'show'])->name('produk.show');
        Route::put('/produk/{product}',     [ProdukController::class, 'update'])->name('produk.update');
        Route::delete('/produk/{product}',  [ProdukController::class, 'destroy'])->name('produk.destroy');
        Route::patch('/produk/{product}/stok', [ProdukController::class, 'updateStok'])->name('produk.updateStok');

        // Company Profile
        Route::get('/company-profile',          [CompanyProfileController::class, 'index'])->name('company-profile');
        Route::put('/company-profile/profil',   [CompanyProfileController::class, 'updateProfil'])->name('company-profile.updateProfil');
        Route::put('/company-profile/kontak',   [CompanyProfileController::class, 'updateKontak'])->name('company-profile.updateKontak');

        // Transaksi / Pesanan
        Route::get('/pesanan',                  [TransactionController::class, 'index'])->name('pesanan');
        Route::get('/transaksi/create',         [TransactionController::class, 'create'])->name('transaksi.create');
        Route::post('/transaksi',               [TransactionController::class, 'store'])->name('transaksi.store');
        Route::get('/transaksi/{transaction}',  [TransactionController::class, 'show'])->name('transaksi.show');
        Route::post('/transaksi/update-payment',  [TransactionController::class, 'updatePaymentStatus'])->name('transaksi.updatePayment');

        // Laporan Penjualan
        Route::get('/penjualan',                [TransactionController::class, 'laporanHarian'])->name('penjualan');
        Route::get('/penjualan/bulanan',        [TransactionController::class, 'laporanBulanan'])->name('penjualan.bulanan');
    });
});