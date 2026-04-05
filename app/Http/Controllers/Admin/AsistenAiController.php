<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\RiwayatStok;
use App\Models\Kategori;
use App\Models\Pembayaran;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class AsistenAiController extends Controller
{
    public function tanya(Request $request){
        $pertanyaanUser = $request->input('pertanyaan');

        // mengumpulkan data dari berbagai table untuk pertanyaan pengguna

        //produk
        $produk = Produk::all();
        //produk terlaris dari dulu sampai sekarang
        $tanggalMulaiProduk = now()->format('1998-01-01');
        
        $totalAsetGudang = Produk::get()->sum(function($p) {
            return $p->stok * $p->harga_beli;
        });
        
        //jumlah total item
        $totalItem = Produk::sum('stok');

        //Riwayat Stok
        //Barang masuk terbaru maupun lama
        $barangMasuk = RiwayatStok::where('tipe', 'restok')->get();
        //barang masuk minggu ini
        $barangMasukMingguIni = RiwayatStok::where('tipe', 'restok')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->get();
        //Barang terjual
        $barangTerjual = RiwayatStok::where('tipe', 'sale')->get();
        //barang koreksi / rusak
        $barangCorrection = RiwayatStok::where('tipe', 'correction')->get();
        //Total Kerugian Akibat Barang Rusak
        $barangKerugianRusak = RiwayatStok::with('produk')->where('tipe', 'correction')->get()->sum(function($item) {
            return abs($item->jumlah) * ($item->produk->harga_beli ?? 0);
        });
        

        //transaksi
        $transaksi = Transaksi::all();
        //top 5 barang terlaris bulan ini
        $barangTerlarisBulanIni = DetailTransaksi::select('produk_id', DB::raw('SUM(jumlah) as total_terjual'))
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->groupBy('produk_id')
        ->orderBy('total_terjual', 'desc')
        ->with('produk')
        ->take(5)->get();
        
        //top 5 produk terlaris minggu ini
        $barangTerlarisMingguan = DetailTransaksi::select('produk_id', DB::raw('SUM(jumlah) as total_terjual'))
        ->where('created_at', '>=', now()->startOfWeek())
        ->groupBy('produk_id')
        ->orderBy('total_terjual', 'desc')
        ->with('produk')
        ->take(5)->get();

        //hasil dari barang terlaris mingguan maupun bulanan
        $teksTerlarisBulan = $barangTerlarisBulanIni->isEmpty() ? "Belum ada penjualan" : $barangTerlarisBulanIni->map(function($item) {
            return ($item->produk->nama_produk ?? 'Dihapus') . " (" . $item->total_terjual . " pcs)";
        })->implode(', ');

        $teksTerlarisMinggu = $barangTerlarisMingguan->isEmpty() ? "Belum ada penjualan" : $barangTerlarisMingguan->map(function($item) {
            return ($item->produk->nama_produk ?? 'Dihapus') . " (" . $item->total_terjual . " pcs)";
        })->implode(', ');
        
        //omzet bulanan
        $omzetBulanan = Transaksi::where('created_at', '>=', now()->startOfMonth())->sum('total_harga');
        //omzet hari ini
        $omzetHariIni = Transaksi::where('created_at', '>=', now()->startOfDay())->sum('total_harga');
        //omzet minggu ini
        $omzetMingguIni = Transaksi::where('created_at', '>=', now()->startOfWeek())->sum('total_harga');
        //omzet tahun ini
        $omzetTahunan = Transaksi::where('created_at', '>=', now()->startOfYear())->sum('total_harga');
        //jumlah transaksi hari ini
        $transaksiNow = Transaksi::where('created_at','>=', now()->startOfDay())->count();

        //kategori
        $kategori = Kategori::all();


        //pembayaran
        $pembayaran = Pembayaran::all();
        //pembayaran paling banyak digunakan minggu ini
        $pembayaranWeek = Transaksi::where('created_at', '>=', now()->startOfWeek())->select('pembayaran_id')->distinct()->get();
        //pembayaran paling banyak digunakan bulan ini
        $pembayaranMonth = Transaksi::where('created_at', '>=', now()->startOfMonth())->select('pembayaran_id')->distinct()->get();

        //prompt gabungan
        $promptGabungan = "Halo Bos, Ini data rekapan semua dari sistem UD Rizki Jaya

            Hasil Rekapan Data Sistem UD Rizki Jaya:

            Produk:
            - Total Aset Gudang: Rp. " . number_format($totalAsetGudang, 0, ',', '.') . "
            - Total Item: " . $totalItem . "
            
            Riwayat Stok:
            - Barang Masuk: " . $barangMasuk->count() . "
            - Barang Masuk Minggu Ini: " . $barangMasukMingguIni->count() . "
            - Barang Terjual: " . $barangTerjual->count() . "
            - Barang Koreksi: " . $barangCorrection->count() . "
            - Total Kerugian Akibat Barang Rusak: Rp. " . number_format($barangKerugianRusak, 0, ',', '.') . "
            
            Transaksi:
            - Top 5 Barang Terlaris Bulan Ini: " . $teksTerlarisBulan . "
            - Top 5 Barang Terlaris Minggu Ini: " . $teksTerlarisMinggu . "
            - Omzet Bulanan: Rp. " . number_format($omzetBulanan, 0, ',', '.') . "
            - Omzet Hari Ini: Rp. " . number_format($omzetHariIni, 0, ',', '.') . "
            - Omzet Minggu Ini: Rp. " . number_format($omzetMingguIni, 0, ',', '.') . "
            - Omzet Tahunan: Rp. " . number_format($omzetTahunan, 0, ',', '.') . "
            - Jumlah Transaksi Hari Ini: " . $transaksiNow . "
            
            Kategori:
            - Jumlah Kategori: " . $kategori->count() . "
            
            Pembayaran:
            - Jumlah Pembayaran: " . $pembayaran->count() . "
            - Pembayaran Paling Banyak Digunakan Minggu Ini: " . $pembayaranWeek->count() . "
            - Pembayaran Paling Banyak Digunakan Bulan Ini: " . $pembayaranMonth->count() . "

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