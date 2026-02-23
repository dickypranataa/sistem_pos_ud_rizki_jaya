<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    //
    protected $fillable =[
        'user_id',
        'pembayaran_id',
        'tipe_harga',
        'total_harga',
        'bayar',
        'kembalian',
    ];

    //1 transaksi dibuat oleh 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //1 transaksi menggunakan 1 pembayaran
    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }

    //1 transaksi bisa terdiri dari banyak detail transaksi
    public function detail()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
}
