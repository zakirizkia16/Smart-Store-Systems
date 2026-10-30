<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';

interface Product {
    id: number;
    kode: string;
    nama: string;
    harga: number;
    stok: number;
    kategori: string;
    gambar_url?: string | null,
}

defineProps<{
    products: Product[];
}>();

const formatRupiah = (angka: number): string => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(angka);
};

const deleteProduct = (id: number) => {
    if (confirm('Yakin ingin menghapus produk ini?')) {
        router.delete(`/admin/products/${id}`);
    }
};

</script>

<template>
    <div class="min-h-screen bg-gray-50 py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-extrabold text-gray-900">⚙️ Dashboard Admin</h1>
                <Link href="/admin/products/create"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                    + Tambah Produk
                </Link>
            </div>

            <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Gambar
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stok</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="product in products" :key="product.id">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <!-- Tampilkan gambar jika ada -->
                                        <img v-if="product.gambar_url" :src="product.gambar_url"
                                            class="h-10 w-10 rounded-md object-cover" />
                                        <!-- Fallback kotak abu-abu jika belum ada gambar -->
                                        <div v-else
                                            class="h-10 w-10 rounded-md bg-gray-200 flex items-center justify-center text-xs text-gray-400">
                                            No Pic
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">{{ product.kode
                                }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ product.nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ product.kategori }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ formatRupiah(product.harga)
                                }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="product.stok > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                    {{ product.stok }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <Link :href="`/admin/products/${product.id}/edit`"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</Link>
                                <button @click="deleteProduct(product.id)"
                                    class="text-red-600 hover:text-red-900">Hapus</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</template>