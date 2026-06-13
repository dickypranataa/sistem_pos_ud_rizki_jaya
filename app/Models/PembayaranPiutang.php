<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranPiutang extends Model
{
    use HasFactory;

    protected $fillable = [
        'piutang_id', 
        'user_id', 
        'jumlah_bayar', 
        'tanggal_bayar'
    ];

    public function piutang()
    {
        return $this->belongsTo(Piutang::class);
    }

    // Relasi ke Kasir/Admin yang menerima uang
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}