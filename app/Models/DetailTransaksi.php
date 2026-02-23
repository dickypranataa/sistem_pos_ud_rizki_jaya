<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    //
    protected $fillable =[
        'transaksi_id',
        'produk_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    //1 detail transaksi terhubung dengan 1 transaksi
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    //1 detail transaksi terhubung dengan 1 produk
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
