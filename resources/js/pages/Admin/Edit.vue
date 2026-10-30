<script setup lang="ts">
import { ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';

interface Product {
    id: number;
    kode: string;
    nama: string;
    harga: number;
    stok: number;
    kategori: string;
    gambar_url?: string | null;
}

const props = defineProps<{
    product: Product;
}>();

const form = useForm({
    kode: props.product.kode,
    nama: props.product.nama,
    kategori: props.product.kategori,
    harga: props.product.harga,
    stok: props.product.stok,
    gambar: null as File | null,
    _method: 'PUT' // Penting: Spoofing PUT method buat Laravel
});

const imagePreview = ref<string | null>(props.product.gambar_url || null);

const handleFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        const file = target.files[0];
        form.gambar = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    // Wajib pakai post() agar forceFormData bekerja
    form.post(`/admin/products/${props.product.id}`, {
        forceFormData: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-sm border border-gray-200">

            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-extrabold text-gray-900">✏️ Edit Produk</h1>
                <Link href="/admin/products" class="text-gray-500 hover:text-gray-700 font-medium">Batal & Kembali
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kode Produk</label>
                    <input v-model="form.kode" type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                    <input v-model="form.nama" type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                    <input v-model="form.kategori" type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                        <input v-model="form.harga" type="number"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Stok</label>
                        <input v-model="form.stok" type="number"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
                    </div>
                </div>

                <!-- Input Upload Gambar -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Produk</label>
                    <input type="file" accept="image/*" @change="handleFileChange"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    <p class="text-xs text-gray-400 mt-1">Biarkan kosong jika tidak ingin mengubah foto.</p>
                </div>

                <!-- Preview Gambar -->
                <div v-if="imagePreview" class="mt-2">
                    <img :src="imagePreview"
                        class="h-32 w-32 object-cover rounded-lg border border-gray-200 shadow-sm" />
                </div>

                <div class="pt-4">
                    <button type="submit" :disabled="form.processing"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg">
                        Update Produk
                    </button>
                </div>
            </form>

        </div>
    </div>
</template>