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
        //
        $pembayaran = [
            ['nama_pembayaran' => 'Tunai'],
            ['nama_pembayaran' => 'QRIS'],
            ['nama_pembayaran' => 'Transfer'],
        ];
        Pembayaran::insert($pembayaran);
    }
}
