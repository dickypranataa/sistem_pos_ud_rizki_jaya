<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;
use App\Models\DetailTransaksi;
use App\Models\RiwayatStok;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //
    public function index()
{
    // 1. Tren Pendapatan 7 Hari Terakhir
    $omzet7Hari = Transaksi::select(
            DB::raw('DATE(waktu_transaksi) as tanggal'),
            DB::raw('SUM(total_harga) as total')
        )
        ->where('waktu_transaksi', '>=', now()->subDays(6))
        ->groupBy('tanggal')->orderBy('tanggal', 'asc')->get();

    // 2. Produk Terlaris Bulan Ini (Top 5)
    $produkTerlaris = DetailTransaksi::select('produk_id', DB::raw('SUM(jumlah) as total_terjual'))
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->groupBy('produk_id')
        ->orderBy('total_terjual', 'desc')
        ->with('produk')
        ->take(5)->get();

    // 3. Komposisi Metode Pembayaran
    $metodePembayaran = Transaksi::select('pembayaran_id', DB::raw('count(*) as total'))
        ->groupBy('pembayaran_id')
        ->with('pembayaran')
        ->get();

    // 4. 5 Riwayat Transaksi Terakhir
    $transaksiTerakhir = Transaksi::with('user', 'pembayaran')->latest()->take(5)->get();

    // 5. 5 Info Riwayat Stok Terakhir
    $stokTerakhir = RiwayatStok::with('produk', 'user')->latest()->take(5)->get();

    // Menghitung produk yang stoknya di bawah 5
    $stokKritis = Produk::where('stok', '<', 5)->count();
    
    // Hitung total omzet bulan ini untuk Stat Card
    $omzetBulanIni = Transaksi::whereMonth('waktu_transaksi', now()->month)->sum('total_harga');
    
    // Hitung total transaksi bulan ini
    $totalTransaksiBulanIni = Transaksi::whereMonth('waktu_transaksi', now()->month)->count();

    // Jangan lupa masukkan ke dalam compact()
    return view('admin.dashboard', compact(
        'omzet7Hari', 'produkTerlaris', 'metodePembayaran', 
        'transaksiTerakhir', 'stokTerakhir', 'stokKritis', 
        'omzetBulanIni', 'totalTransaksiBulanIni'
    ));
}
}
