<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Product::insert([
            ['kode' => 'P001', 'nama' => 'Sepatu Sneakers Kasual Putih - 42', 'harga' => 350000, 'stok' => 5, 'kategori' => 'Sepatu'],
            ['kode' => 'P002', 'nama' => 'Sepatu Sneakers Kasual Putih - 43', 'harga' => 350000, 'stok' => 0, 'kategori' => 'Sepatu'],
            ['kode' => 'P003', 'nama' => 'Kaos Polos Hitam Cotton Combed - L', 'harga' => 85000, 'stok' => 20, 'kategori' => 'Pakaian'],
            ['kode' => 'P004', 'nama' => 'Kaos Polos Hitam Cotton Combed - XL', 'harga' => 90000, 'stok' => 2, 'kategori' => 'Pakaian'],
            ['kode' => 'P005', 'nama' => 'Jaket Hoodie Fleece Tebal - M', 'harga' => 175000, 'stok' => 8, 'kategori' => 'Pakaian'],
            ['kode' => 'P006', 'nama' => 'Kemeja Flanel Kotak-kotak', 'harga' => 150000, 'stok' => 12, 'kategori' => 'Pakaian'],
            ['kode' => 'P007', 'nama' => 'Topi Baseball Polos Hitam', 'harga' => 45000, 'stok' => 15, 'kategori' => 'Aksesoris'],
            ['kode' => 'P008', 'nama' => 'Celana Chino Panjang - 32', 'harga' => 190000, 'stok' => 0, 'kategori' => 'Pakaian'],
            ['kode' => 'P009', 'nama' => 'Sabuk Kulit Pria Asli', 'harga' => 120000, 'stok' => 5, 'kategori' => 'Aksesoris'],
            ['kode' => 'P010', 'nama' => 'Tas Ransel Laptop 15 inch', 'harga' => 275000, 'stok' => 6, 'kategori' => 'Aksesoris'],
        ]);
    }
}
