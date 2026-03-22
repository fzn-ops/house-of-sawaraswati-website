<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Middleware\AdminMiddleware;

// Katalog
Route::get('/katalog/{slug}', fn ($slug) => view('pages.katalog.show', compact('slug')))->name('katalog.show');
Route::get('/katalog',     fn () => view('pages.katalog.katalog_index'))->name('katalog');

// Beranda / Landing Page
Route::get('/', fn () => view('pages.home'))->name('home');

// Kontak
Route::get('/kontak', fn () => view('pages.contact'))->name('contact');

// ===== ADMIN ROUTES =====
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/',        [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login',  [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
 
    // Protected routes (perlu login)
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');
        Route::get('/pesanan',   fn () => view('admin.pesanan'))->name('pesanan');
        Route::get('/produk',    fn () => view('admin.produk'))->name('produk');
        Route::get('/penjualan', fn () => view('admin.penjualan'))->name('penjualan');
    });
});
 