<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pelanggan', 
        'alamat', 
        'no_hp'
    ];

    // Relasi: Satu Pelanggan bisa memiliki banyak Piutang
    public function piutangs()
    {
        return $this->hasMany(Piutang::class);
    }
}