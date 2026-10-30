<script setup lang="ts">
import { ref, computed } from 'vue';

// Definisikan tipe data untuk Produk
interface Product {
  id: number;
  kode: string;
  nama: string;
  harga: number;
  stok: number;
  kategori: string;
  gambar_url?: string | null;
}

// Definisikan tipe data untuk Props dari Inertia
const props = defineProps<{
  products: Product[];
  waNumber: string;
}>();

// --- STATE PENCARIAN & FILTER ---
const searchQuery = ref<string>('');
const selectedCategory = ref<string>('');

// --- COMPUTED PROPERTIES ---

// 1. Ekstrak kategori unik dari data produk untuk dropdown
const uniqueCategories = computed(() => {
  const categories = props.products.map(p => p.kategori);
  // Gunakan Set untuk membuang duplikat, lalu ubah kembali jadi array
  return [...new Set(categories)].filter(Boolean); // filter(Boolean) untuk jaga-jaga kalau ada yg null/undefined
});

// 2. Filter produk berdasarkan pencarian nama/kode DAN kategori
const filteredProducts = computed(() => {
  return props.products.filter((product) => {
    // Cek Search Query (cocokkan dengan nama atau kode)
    const matchesSearch =
      product.nama.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      product.kode.toLowerCase().includes(searchQuery.value.toLowerCase());

    // Cek Kategori
    const matchesCategory =
      selectedCategory.value === '' || product.kategori === selectedCategory.value;

    return matchesSearch && matchesCategory;
  });
});

// --- HELPER FUNCTIONS ---

// Format angka ke Rupiah
const formatRupiah = (angka: number): string => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(angka);
};

// Generate Link WA dinamis sesuai produk
const getWaLink = (product: Product): string => {
  const text = `Halo Kak, saya mau tanya stok untuk produk *${product.nama}* (${product.kode}). Apakah masih ada?`;
  return `https://wa.me/${props.waNumber}?text=${encodeURIComponent(text)}`;
};
</script>

<template>
  <div class="min-h-screen bg-gray-50 py-10 px-4 sm:px-6 lg:px-8">
    <!-- Header Toko -->
    <div class="max-w-7xl mx-auto mb-8 text-center">
      <h1 class="text-4xl font-extrabold text-gray-900 mb-2">🛍️ Smart Store Katalog</h1>
      <p class="text-gray-500">Tanya stok dan pesan langsung via WhatsApp AI kami!</p>
    </div>

    <!-- Search & Filter Controls -->
    <div
      class="max-w-7xl mx-auto mb-8 flex flex-col sm:flex-row gap-4 justify-between items-center bg-white p-4 rounded-xl shadow-sm border border-gray-100">

      <!-- Search Input -->
      <div class="w-full sm:w-1/2 relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <input v-model="searchQuery" type="text" placeholder="Cari nama atau kode produk..."
          class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out" />
      </div>

      <!-- Category Filter -->
      <div class="w-full sm:w-1/3">
        <select v-model="selectedCategory"
          class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out">
          <option value="">Semua Kategori</option>
          <option v-for="cat in uniqueCategories" :key="cat" :value="cat">
            {{ cat }}
          </option>
        </select>
      </div>

    </div>

    <!-- Empty State (Jika hasil pencarian tidak ada) -->
    <div v-if="filteredProducts.length === 0" class="max-w-7xl mx-auto text-center py-12">
      <p class="text-gray-500 text-lg">Oops! Produk yang kamu cari tidak ditemukan. 🕵️‍♂️</p>
      <button @click="searchQuery = ''; selectedCategory = ''" class="mt-4 text-blue-600 hover:underline">
        Reset Pencarian
      </button>
    </div>



    <!-- Grid Produk -->
    <div v-else class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

      <!-- Card Produk (Menggunakan filteredProducts) -->
      <div v-for="product in filteredProducts" :key="product.id"
        class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300 flex flex-col">
        <!-- Gambar Produk / Fallback -->
        <div class="h-48 bg-gray-100 w-full relative overflow-hidden flex items-center justify-center">
          <img v-if="product.gambar_url" :src="product.gambar_url" :alt="product.nama"
            class="w-full h-full object-cover transition-transform duration-300 hover:scale-105" />
          <span v-else class="text-gray-400 text-sm font-medium">
            Gambar {{ product.kategori }}
          </span>

          <!-- Badge Stok -->
          <span v-if="product.stok > 5"
            class="absolute top-3 right-3 bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full shadow-sm">
            Tersedia
          </span>
          <span v-else-if="product.stok > 0 && product.stok <= 5"
            class="absolute top-3 right-3 bg-yellow-100 text-yellow-700 text-xs font-bold px-2.5 py-1 rounded-full shadow-sm">
            Sisa {{ product.stok }}
          </span>
          <span v-else
            class="absolute top-3 right-3 bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-full shadow-sm">
            Habis
          </span>
        </div>

        <!-- Info Produk -->
        <div class="p-5 flex flex-col flex-grow">
          <div class="flex justify-between items-center mb-1">
            <div class="text-xs text-blue-600 font-semibold">{{ product.kode }}</div>
            <!-- Menambahkan Badge Kategori Kecil di dalam card (Opsional) -->
            <div class="text-[10px] text-gray-400 uppercase tracking-wider">{{ product.kategori }}</div>
          </div>

          <h3 class="text-lg font-bold text-gray-900 mb-2 leading-tight">{{ product.nama }}</h3>
          <p class="text-xl font-extrabold text-gray-800 mb-4">{{ formatRupiah(product.harga) }}</p>

          <!-- Spacer untuk dorong tombol ke bawah -->
          <div class="flex-grow"></div>

          <!-- Tombol CTA -->
          <a :href="getWaLink(product)" target="_blank" :class="[
            'w-full py-2.5 px-4 rounded-lg font-semibold text-sm text-center transition-colors',
            product.stok > 0
              ? 'bg-blue-600 hover:bg-blue-700 text-white'
              : 'bg-gray-200 text-gray-500 cursor-not-allowed pointer-events-none'
          ]">
            {{ product.stok > 0 ? 'Tanya via WA' : 'Stok Habis' }}
          </a>
        </div>
      </div>

    </div>
  </div>
</template>