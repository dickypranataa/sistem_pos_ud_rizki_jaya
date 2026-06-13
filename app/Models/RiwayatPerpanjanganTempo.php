<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPerpanjanganTempo extends Model
{
    use HasFactory;

    protected $fillable = [
        'piutang_id', 
        'user_id', 
        'tempo_lama', 
        'tempo_baru', 
        'alasan_perpanjangan'
    ];

    public function piutang()
    {
        return $this->belongsTo(Piutang::class);
    }

    // Relasi ke Admin yang memberikan persetujuan perpanjangan
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}