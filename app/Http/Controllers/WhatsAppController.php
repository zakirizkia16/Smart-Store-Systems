<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Gemini\Laravel\Facades\Gemini;

class WhatsAppController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // 1. Ambil data pesan masuk dari WA Gateway (contoh payload standar)
        $sender = $request->input('sender'); // Nomor pengirim
        $userMessage = $request->input('message'); // Isi pesan chat

        if (empty($userMessage)) {
            return response()->json(['status' => 'ignored', 'message' => 'No message content'], 200);
        }

        // 2. Data Katalog Dummy (Nanti bisa diganti query DB / Google Sheets API)
        $katalogData = "
        DATA KATALOG PRODUK TOKO:
        - P001 | Sepatu A - Ukuran 42 | Harga: Rp 350.000 | Stok: 5 (TERSEDIA)
        - P002 | Sepatu A - Ukuran 43 | Harga: Rp 350.000 | Stok: 0 (HABIS)
        - P003 | Kaos Polos Hitam - L | Harga: Rp 85.000 | Stok: 20 (TERSEDIA)
        - P004 | Kaos Polos Hitam - XL | Harga: Rp 90.000 | Stok: 2 (MENIPIS)
        - P005 | Jaket Hoodie Fleece - M | Harga: Rp 175.000 | Stok: 8 (TERSEDIA)
        - P008 | Celana Chino Panjang - 32 | Harga: Rp 190.000 | Stok: 0 (HABIS)
        - P010 | Tas Ransel Laptop 15 inch | Harga: Rp 275.000 | Stok: 6 (TERSEDIA)
        ";

        // 3. System Instructions (Otak AI)
        $systemInstructions = "
        Kamu adalah asisten WhatsApp resmi untuk toko pakaian dan aksesoris kasual. Tugasmu HANYA melayani pertanyaan seputar produk, harga, stok, dan pemesanan di toko ini.

        ATURAN GUARDRAILS (BATASAN TOPIK):
        1. Fokus utama kamu HANYA melayani pertanyaan seputar produk, harga, stok, dan pemesanan di toko ini.
        2. JANGAN PERNAH menjawab atau melayani pertanyaan di luar produk toko (seperti cuaca, lelucon, resep makanan, curhat, dll).
        3. Jika ada pembeli yang bertanya di luar topik tersebut, tolaklah dengan sopan secara natural menggunakan gaya bahasa kasual (panggil 'Kak'), lalu langsung arahkan kembali percakapan ke katalog produk toko.
        4. Jawab stok/harga HANYA dari data katalog. Jika stok habis (0), rekomendasikan varian lain yang ready.
        ";

        // 4. Gabungkan Prompt & Panggil Gemini API
        $fullPrompt = $systemInstructions . "\n\n" . $katalogData . "\n\nPertanyaan Pembeli: " . $userMessage;

        try {
            $result = Gemini::geminiFlash()->generateContent($fullPrompt);
            $aiReply = $result->text();

            // 5. Kirim balasan AI balik ke WhatsApp Gateway (misal via Fonnte)
            $this->sendWhatsAppMessage($sender, $aiReply);

            return response()->json(['status' => 'success', 'reply' => $aiReply], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // Helper untuk kirim pesan balik via WA Gateway
    private function sendWhatsAppMessage($target, $message)
    {
        // Contoh integrasi Fonnte (Tinggal ganti TOKEN & URL sesuai provider WA Gateway kamu)
        /*
        Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN'),
        ])->post('https://api.fonnte.com/send', [
            'target' => $target,
            'message' => $message,
        ]);
        */
    }
}
