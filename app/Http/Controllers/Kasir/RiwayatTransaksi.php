<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;


class RiwayatTransaksi extends Controller
{
    //
    public function index(Request $request){
        //filter bulan

        $filterBulan = $request->input('filter_bulan');
        $filterTanggal = $request->input('filter_tanggal');

    $transaksi = Transaksi::with(['user', 'pembayaran'])
        ->where('user_id', Auth::id())
        ->when($filterBulan, function ($query) use ($filterBulan) {
            $waktu = explode('-', $filterBulan);
            
            if (count($waktu) == 2) {
                $query->whereYear('waktu_transaksi', $waktu[0])
                      ->whereMonth('waktu_transaksi', $waktu[1]);
            }
        })
        ->when($filterTanggal, function ($query) use ($filterTanggal) {
            $query->whereDate('waktu_transaksi', $filterTanggal);
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('kasir.riwayat.index', compact('transaksi'));
}

    public function show($id){
        $transaksi = Transaksi::with(['detail.produk', 'user', 'pembayaran'])->findOrFail($id);
        
        
        return view('kasir.riwayat.show', compact('transaksi'));
    }

    public function cetakStruk($id){
        $transaksi = Transaksi::with(['detail.produk', 'user', 'pembayaran'])->findOrFail($id);

        return view('kasir.riwayat.cetak', compact('transaksi'));
    }

}
