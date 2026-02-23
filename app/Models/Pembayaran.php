<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    //
    protected $fillable = [
        'nama_pembayaran',
    ];

    //1 pembayaran bisa terdapat di banyak transaksi
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}
