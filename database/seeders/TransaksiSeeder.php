<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use App\Models\User;
use App\Models\Pembayaran; 
use Carbon\Carbon;
use Faker\Factory as Faker;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        //data dummy transaksi dan detail transaksi saling berkaitan
        $faker = Faker::create('id_ID');

        //Transaksi
        
        //ambil data produk
        $produks = Produk::all();
        $userIds = User::pluck('id')->toArray();
        $pembayaranIds = Pembayaran::pluck('id')->toArray();

        $pembayaranIds = [1,2,3];

        if (empty($produks) || empty($userIds) || empty($pembayaranIds)) {
            $this->command->info("Gagal: Pastikan tabel produk, user dan pembayaran sudah terisi");
            return;
        }

        $this->command->info('Memulai simulasi 7.000 Transaksi untuk Machine Learning...');
        $this->command->info('Mohon tunggu, ini mungkin memakan waktu 1-2 menit...');

        DB::beginTransaction();
        try {
            $countTransaksi = 7000;
            //perulangan sesuai dengan jumlah transaksi = 7000
            for ($i=1; $i <= $countTransaksi; $i++) {
                //membuat tanggal transaksi acak 2 tahun sampai sekarang
                $waktu = $faker->dateTimeBetween('-2 year', 'now');
                //menambil tipe harga secara acak
                $tipe_harga = $faker->randomElement(['retail', 'semi_grosir', 'grosir']);

                $transaksi = Transaksi::create([
                    'kode_transaksi'  => 'TRX-' . $waktu->format('Ymd') . '-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                    'pembayaran_id'   => $faker->randomElement($pembayaranIds),
                    'user_id'         => $faker->randomElement($userIds),
                    'waktu_transaksi' => $waktu,
                    'tipe_harga'      => $tipe_harga,
                    'total_harga'     => 0,
                    'bayar'           => 0,
                    'kembalian'       => 0, 
                    'created_at'      => $waktu,
                    'updated_at'      => $waktu,
                ]);

                //detail transaksi
                //menentukan pelanggan belu berapa macam barang (1-5 macam)
                $jumlahMacamBarang = rand(1,5);

                //pilih porduk acak sebanyak jumlahmacambarang
                $randomProduks = $produks->random($jumlahMacamBarang);

                $totalHargaTransaksi = 0;

                foreach ($randomProduks as $produk){
                    // tentukan jumlah beli (1 sampai 10)
                    $qty = rand(1,10);

                    //ambil harga asli sesuai tipe pelanggan
                    $hargaSatuan = 0;
                    if ($tipe_harga === 'retail'){
                        $hargaSatuan = $produk->harga_retail;
                    }elseif($tipe_harga === 'semi_grosir'){
                        $hargaSatuan = $produk->harga_semi_grosir;
                    }elseif($tipe_harga === 'grosir'){
                        $hargaSatuan = $produk->harga_grosir;
                    }

                    //hitung subtotal
                    $subtotal =  $qty * $hargaSatuan;
                    $totalHargaTransaksi += $subtotal;

                    //simpan detail transaksi
                    DetailTransaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'produk_id'    => $produk->id,
                        'jumlah'       => $qty,
                        'harga_satuan' => $hargaSatuan,
                        'subtotal'     => $subtotal,
                        'created_at'   => $waktu,
                        'updated_at'   => $waktu,
                    ]);

                }

                //  PEMBAYARAN
                // Kasus : 40% uang pas, 60% pakai uang pecahan besar
                if ($faker->boolean(40)){
                    $uangBayar = $totalHargaTransaksi;
                    
                }else{
                    //pakai uang pecahan besar
                    $pecahan = $faker->randomElement([50000, 100000, 200000]);
                    $uangBayar = ceil($totalHargaTransaksi / $pecahan) * $pecahan;

                    //failsafe jika uang bayar pas
                    if ($uangBayar == $totalHargaTransaksi){
                        $uangBayar += $pecahan;
                    }
                }

                $kembalian = $uangBayar - $totalHargaTransaksi;
                // Update kerangka transaksi dengan angka yang sudah fix
                $transaksi->update([
                    'total_harga' => $totalHargaTransaksi,
                    'bayar'       => $uangBayar,
                    'kembalian'   => $kembalian,
                ]);
            }

            DB::commit();
            $this->command->info("Simulasi 7.000 Transaksi Selesai!");

            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Gagal: " . $e->getMessage());
        }
    }
}