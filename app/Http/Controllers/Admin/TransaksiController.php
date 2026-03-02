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
    public function index(){
        $search = request('search');
        $transaksi = Transaksi::query()->when($search, function ($query) use ($search) {
            $query->where('kode_transaksi', 'like', "%{$search}%");})
                  ->latest()
                  ->paginate(10);
        return view('admin.transaksi.index', compact('transaksi'));
    }

    public function show($id){
        $transaksi = Transaksi::find($id);
        $detail = DetailTransaksi::where('transaksi_id', $id)->get();
        return view('admin.transaksi.show', compact('transaksi', 'detail'));
    }


}
