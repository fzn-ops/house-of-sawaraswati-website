<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    // getProduk() : Product (list semua produk)
    public function index()
    {
        $products = Product::all();
        return view('admin.produk', compact('products'));
    }

    // getDetailProduk() : Product
    public function show(Product $product)
    {
        return response()->json($product);
    }

    // tambahProduk() : void
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only('name', 'description', 'price', 'stok');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.produk')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    // ubahProduk() : void
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stok'        => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only('name', 'description', 'price', 'stok');

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.produk')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    // hapusProduk() : void
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('admin.produk')
            ->with('success', 'Produk berhasil dihapus.');
    }

    // updateStok() : void
    public function updateStok(Request $request, Product $product)
    {
        $request->validate([
            'stok' => 'required|integer|min:0',
        ]);

        $product->update(['stok' => $request->stok]);

        return redirect()->route('admin.produk')
            ->with('success', 'Stok produk berhasil diperbarui.');
    }
}
