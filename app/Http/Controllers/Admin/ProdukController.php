<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $products = Product::with('sizes')->orderByDesc('product_id')->paginate(24)->withQueryString();
        return view('admin.produk', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load('sizes');
        return response()->json($product);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'category'      => 'nullable|string|max:100',
            'price'         => 'required|numeric|min:0',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sizes'         => 'required|array|min:1',
            'sizes.*.size'  => 'required|string|max:50',
            'sizes.*.stok'  => 'required|integer|min:0',
        ]);

        $sizes = $this->normalizeSizes($request->input('sizes', []));

        if (empty($sizes)) {
            return back()->withInput()->with('error', 'Minimal satu size harus diisi.');
        }

        $data = $request->only('name', 'description', 'category', 'price');
        $data['stok'] = array_sum(array_column($sizes, 'stok'));

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        DB::transaction(function () use ($data, $sizes) {
            $product = Product::create($data);
            $product->sizes()->createMany($sizes);
        });

        return redirect()->route('admin.produk')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'category'      => 'nullable|string|max:100',
            'price'         => 'required|numeric|min:0',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sizes'         => 'required|array|min:1',
            'sizes.*.size'  => 'required|string|max:50',
            'sizes.*.stok'  => 'required|integer|min:0',
        ]);

        $sizes = $this->normalizeSizes($request->input('sizes', []));

        if (empty($sizes)) {
            return back()->withInput()->with('error', 'Minimal satu size harus diisi.');
        }

        $data = $request->only('name', 'description', 'category', 'price');
        $data['stok'] = array_sum(array_column($sizes, 'stok'));

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        DB::transaction(function () use ($product, $data, $sizes) {
            $product->update($data);
            $product->sizes()->delete();
            $product->sizes()->createMany($sizes);
        });

        return redirect()->route('admin.produk')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('admin.produk')
            ->with('success', 'Produk berhasil dihapus.');
    }

    private function normalizeSizes(array $rawSizes): array
    {
        $merged = [];
        foreach ($rawSizes as $row) {
            $size = trim((string) ($row['size'] ?? ''));
            $stok = (int) ($row['stok'] ?? 0);
            if ($size === '') {
                continue;
            }
            $merged[$size] = ($merged[$size] ?? 0) + $stok;
        }

        $result = [];
        foreach ($merged as $size => $stok) {
            $result[] = ['size' => $size, 'stok' => $stok];
        }
        return $result;
    }
}
