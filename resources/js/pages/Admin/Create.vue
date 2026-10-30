<script setup lang="ts">
import { ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';

const imagePreview = ref<string | null>(null);

// Inisialisasi state form menggunakan helper Inertia
const form = useForm({
  kode: '',
  nama: '',
  kategori: '',
  harga: 0,
  stok: 0,
  gambar: null as File | null,
});

const handleFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement;
  if (target.files && target.files[0]) {
    const file = target.files[0];
    form.gambar = file;
    imagePreview.value = URL.createObjectURL(file);
  }
};

// Fungsi untuk submit data
const submit = () => {
  form.post('/admin/products', {
    forceFormData: true, //Wajib jika mengirim file
    onSuccess: () => {
      form.reset();
      imagePreview.value = null;
    },
  });
};
</script>

<template>
  <div class="min-h-screen bg-gray-50 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-sm border border-gray-200">

      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-extrabold text-gray-900">📝 Tambah Produk Baru</h1>
        <Link href="/admin/products" class="text-gray-500 hover:text-gray-700 font-medium">Batal & Kembali</Link>
      </div>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Kode Produk</label>
          <input v-model="form.kode" type="text"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
            required>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
          <input v-model="form.nama" type="text"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
            required>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Kategori</label>
          <input v-model="form.kategori" type="text"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
            required>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
            <input v-model="form.harga" type="number"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
              required>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Stok</label>
            <input v-model="form.stok" type="number"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
              required>
          </div>
        </div>

        <!-- Input File Gambar -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Foto Produk</label>
          <input type="file" accept="image/*" @change="handleFileChange"
            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
        </div>

        <!-- Image Preview -->
        <div v-if="imagePreview" class="mt-2">
          <img :src="imagePreview" class="h-32 w-32 object-cover rounded-lg border border-gray-200" />
        </div>

        <div class="pt-4">
          <button type="submit" :disabled="form.processing"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg">
            Simpan Produk
          </button>
        </div>
      </form>

    </div>
  </div>
</template>