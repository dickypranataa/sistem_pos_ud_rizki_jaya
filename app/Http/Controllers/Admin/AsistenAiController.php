<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\RiwayatStok;
use Illuminate\Support\Facades\Http;

class AsistenAiController extends Controller
{
    public function tanya(Request $request){
        $pertanyaanUser = $request->input('pertanyaan');

        // --- 1. KUMPULKAN LEBIH BANYAK DATA DARI DATABASE ---
        
        // Data Hari Ini
        $omzetHariIni = Transaksi::whereDate('waktu_transaksi', today())->sum('total_harga');
        $trxHariIni = Transaksi::whereDate('waktu_transaksi', today())->count();
        
        // Data 7 Hari Terakhir
        $tanggalMulaiMinggu = now()->subDays(7);
        $omzetMingguIni = Transaksi::where('waktu_transaksi', '>=', $tanggalMulaiMinggu)->sum('total_harga');
        $trxMingguIni = Transaksi::where('waktu_transaksi', '>=', $tanggalMulaiMinggu)->count();

        // Data Stok Kritis
        $stokKritis = Produk::where('stok', '<', 5)->get()->map(function($p) {
            return $p->nama_produk . ' (sisa ' . $p->stok . ')';
        })->implode(', ');

        // Jika stok kritis kosong, ubah teksnya agar AI tidak bingung
        $stokKritis = empty($stokKritis) ? "Semua stok aman (tidak ada di bawah 5)." : $stokKritis;

        // KOREKSI STOK
        // 1. Gunakan 'created_at' untuk tanggal
        // 2. Gunakan 'koreksi' pada tipe (sesuai jenis transaksi riwayat)
        // 3. Tambahkan 'with('produk')' agar tidak error saat memanggil nama produk
        $tanggalMulaiKoreksi = now()->subDays(7);
        
        $riwayatStok = RiwayatStok::with('produk')
            ->where('created_at', '>=', $tanggalMulaiKoreksi)
            ->where(function($query) {
                // Ambil data yang tipenya BUKAN sale (penjualan) dan BUKAN restock
                $query->whereNotIn('tipe', ['sale', 'restock'])
                      // ATAU ambil yang keterangannya mengandung kata 'rusak'
                      ->orWhere('keterangan', 'like', '%rusak%'); 
            })
            ->get();
            
        $koreksiStok = $riwayatStok->map(function($r) {
            $namaProduk = $r->produk->nama_produk ?? 'Produk Dihapus';
            // Tambahkan tanggal kejadian agar AI tahu persis kapan barang itu rusak
            $tgl = \Carbon\Carbon::parse($r->created_at)->format('d M'); 
            return "[$tgl] " . $namaProduk . ' (Jumlah: ' . $r->jumlah . ', Ket: ' . $r->keterangan . ')';
        })->implode(' | ');

        // Jika tidak ada data
        $stokKritis = empty($stokKritis) ? "Semua stok aman." : $stokKritis;
        $koreksiStok = empty($koreksiStok) ? "Tidak ada catatan barang rusak/hilang minggu ini." : $koreksiStok;

        // --- 2. BISIKKAN SEMUA DATA ITU KE GEMINI ---
        $promptGabungan = "Anda adalah Asisten Bisnis cerdas, ahli strategi retail, untuk toko bangunan UD Rizki Jaya. 
        
        Konteks Data Toko Saat Ini:
        - Penjualan HARI INI: Omzet Rp " . number_format($omzetHariIni, 0, ',', '.') . " dari $trxHariIni transaksi.
        - Penjualan 7 HARI TERAKHIR: Omzet Rp " . number_format($omzetMingguIni, 0, ',', '.') . " dari $trxMingguIni transaksi.
        - Peringatan Stok (Harus segera restock): $stokKritis.
        - Riwayat Koreksi / Barang Rusak (7 Hari Terakhir): $koreksiStok.
        
        Tugas Anda: Jawablah pertanyaan pengguna berikut ini dengan gaya bahasa profesional, ramah, dan solutif.
        
        Pertanyaan Pengguna: $pertanyaanUser";

        // --- 3. KIRIM KE API GEMINI ---
        $apiKey = trim(env('GEMINI_API_KEY'));
        
        if (!$apiKey) {
            return response()->json(['jawaban' => '🚨 SISTEM: GEMINI_API_KEY tidak ditemukan!']);
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, [
                'contents' => [
                    ['parts' => [['text' => $promptGabungan]]]
                ]
            ]);

            if (!$response->successful()) {
                return response()->json(['jawaban' => '🚨 ERROR DARI GOOGLE: ' . $response->body()]);
            }

            $jawabanAi = $response->json('candidates.0.content.parts.0.text');

            return response()->json(['jawaban' => $jawabanAi ?? 'Maaf, saya tidak bisa merangkai jawaban.']);

        } catch (\Exception $e) {
            return response()->json(['jawaban' => '🚨 ERROR JARINGAN: ' . $e->getMessage()]);
        }
    }
}
