<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    //
    protected $fillable = [
        'kategori_id',
        'sku',
        'nama_produk',
        'satuan',
        'stok',
        'harga_modal',
        'harga_retail',
        'harga_semi_grosir',
        'harga_grosir',
        'gambar',
    ];

    //1 produk terhubung dengan 1 kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    //1 produk bisa memiliki banyak transaksi
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}
