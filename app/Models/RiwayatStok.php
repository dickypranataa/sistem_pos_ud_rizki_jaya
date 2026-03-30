<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatStok extends Model
{
    //
    protected $fillable = [
        'produk_id',
        'user_id',
        'tipe',
        'jumlah',
        'stok_akhir',
        'keterangan',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
