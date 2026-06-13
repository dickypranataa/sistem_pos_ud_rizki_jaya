<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piutang extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id', 
        'pelanggan_id', 
        'sisa_tagihan', 
        'status', 
        'jatuh_tempo'
    ];

    // Relasi ke Transaksi Induk
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    // Relasi ke Pelanggan yang berhutang
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    // Relasi: Satu nota Piutang bisa memiliki banyak riwayat cicilan
    public function pembayaranPiutangs()
    {
        return $this->hasMany(PembayaranPiutang::class);
    }

    // Relasi: Satu nota Piutang bisa diperpanjang beberapa kali
    public function riwayatPerpanjanganTempos()
    {
        return $this->hasMany(RiwayatPerpanjanganTempo::class);
    }
}