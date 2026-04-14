<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function home()
    {
        // 3 produk terbaru untuk unggulan
        $featuredProducts = Product::latest()->take(3)->get();
        $profile = CompanyProfile::first();

        return view('pages.home', compact('featuredProducts', 'profile'));
    }

    public function katalog()
    {
        // Menggunakan get() semua produk agar script JS di frontend tetap bisa nge-filter. 
        // Jika pakai pagination, script JS filter bawaan katalog.js tidak akan bekerja maksimal 
        // karena elemennya tidak ada di DOM.
        $products = Product::latest()->get();
        return view('pages.katalog.katalog_index', compact('products'));
    }

    public function show(Product $product)
    {
        // Get related products (produk lain untuk rekomendasi)
        $relatedProducts = Product::where('product_id', '!=', $product->product_id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('pages.katalog.show', compact('product', 'relatedProducts'));
    }

    public function contact()
    {
        $profile = CompanyProfile::first();
        return view('pages.contact', compact('profile'));
    }
}
