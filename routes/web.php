<?php

use Illuminate\Support\Facades\Route;

// Beranda / Landing Page
Route::get('/', fn () => view('pages.home'))->name('home');

// Produk
Route::get('/produk',      fn () => view('pages.products.index'))->name('products.index');

// Hijab
Route::get('/hijab',       fn () => view('pages.hijab.index'))->name('hijab.index');
Route::get('/hijab/{slug}',fn () => view('pages.hijab.show'))->name('hijab.show');

// Gamis
Route::get('/gamis',       fn () => view('pages.gamis.index'))->name('gamis.index');
Route::get('/gamis/{slug}',fn () => view('pages.gamis.show'))->name('gamis.show');

// Aksesoris
Route::get('/aksesoris',        fn () => view('pages.aksesoris.index'))->name('aksesoris.index');
Route::get('/aksesoris/{slug}', fn () => view('pages.aksesoris.show'))->name('aksesoris.show');

// Kontak
Route::get('/kontak', fn () => view('pages.contact'))->name('contact');