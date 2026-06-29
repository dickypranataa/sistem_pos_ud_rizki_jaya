<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    //
    public function index(){
        // Menghitung berapa transaksi yang sudah diselesaikan kasir ini pada hari ini
        $hariIni = Carbon::today();
        $transaksiSaya = Transaksi::where('user_id', Auth::user()->id)
                                  ->whereDate('waktu_transaksi', $hariIni)
                                  ->count();

        $omzetHariIni = Transaksi::where('user_id', Auth::user()->id)
                                  ->whereDate('waktu_transaksi', $hariIni)
                                  ->sum('total_harga');

        return view('kasir.dashboard', compact('transaksiSaya', 'omzetHariIni'));
    }

    public function cetakStruk($id)
    {
        // Kasir hanya boleh cetak struk milik transaksinya sendiri
        $transaksi = Transaksi::where('user_id', Auth::user()->id)
            ->with(['detail.produk', 'user', 'pembayaran'])
            ->findOrFail($id);
        return view('kasir.struk', compact('transaksi'));
    }
}
