<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use App\Models\User;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Faker\Factory as Faker;

class TransaksiController extends Controller
{
    //
    public function index(Request $request){
        //filter bulan

        $filterBulan = $request->input('filter_bulan'); 

    $transaksi = Transaksi::with(['user', 'pembayaran'])
        ->when($filterBulan, function ($query) use ($filterBulan) {
            $waktu = explode('-', $filterBulan);
            
            if (count($waktu) == 2) {
                $query->whereYear('waktu_transaksi', $waktu[0])
                      ->whereMonth('waktu_transaksi', $waktu[1]);
            }
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('admin.transaksi.index', compact('transaksi'));
}

    public function show($id){
        $transaksi = Transaksi::with(['detail.produk', 'user', 'pembayaran'])->findOrFail($id);
        
        
        return view('admin.transaksi.show', compact('transaksi'));
    }

    public function cetakStruk($id){
        $transaksi = Transaksi::with(['detail.produk', 'user', 'pembayaran'])->findOrFail($id);

        return view('admin.transaksi.cetak', compact('transaksi'));
    }


}
