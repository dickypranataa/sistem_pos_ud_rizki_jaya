<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pembayaran;

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pembayaran = [
            'Tunai',
            'Non Tunai',
            'Piutang',
        ];

        foreach ($pembayaran as $nama) {
            Pembayaran::firstOrCreate(['nama_pembayaran' => $nama]);
        }
    }
}
