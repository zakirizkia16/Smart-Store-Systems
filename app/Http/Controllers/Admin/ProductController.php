<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        // Ambil semua produk, urutkan dari yang terbaru
        $products = Product::latest()->get();

        return Inertia::render('Admin/Index', [
            'products' => $products
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Create');
    }

    public function store(Request $request)
    {
        // Validasi data yang masuk
        $validated = $request->validate([
            'kode' => 'required|string|unique:products,kode|max:255',
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // Max 2MB
        ]);


        // Simpan ke database
        if ($request->hasFile('gambar')) {
            // Simpan ke storage/app/public/products
            $path = $request->file('gambar')->store('products', 'public');
            $validated['gambar'] = $path;
        }

        Product::create($validated);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
    }
    public function edit(Product $product)
    {
        return Inertia::render('Admin/Edit', [
            'product' => $product
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            // Validasi unique dikecualikan untuk ID produk yang sedang diedit
            'kode' => 'required|string|max:255|unique:products,kode,' . $product->id,
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($product->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }
            $path = $request->file('gambar')->store('products', 'public');
            $validated['gambar'] = $path;
        }

        $product->update($validated);
        return redirect('/admin/products');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect('/admin/products');
    }
}
