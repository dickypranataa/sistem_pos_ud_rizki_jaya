<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Produk;
use App\Models\Pembayaran;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\RiwayatStok;
use App\Models\User;
use App\Notifications\StokNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

class KasirTransaksi extends Component
{
    // Variabel untuk menyimpan data sementara (State)
    public $search = '';
    public $keranjang = [];
    public $total_harga = 0;
    public $bayar = 0;
    public $kembalian = 0;

    // Default pilihan tipe harga dan pembayaran
    public $tipe_harga = 'retail';
    public $pembayaran_id = '';

    // Fungsi ini dijalankan setiap kali ada perubahan pada keranjang atau input bayar
    public function updated($property)
    {
        if ($property === 'bayar' || $property === 'tipe_harga' || str_starts_with($property, 'keranjang')) {
            $this->hitungTotal();
        }
    }

    // Fungsi untuk memasukkan barang ke keranjang
    public function tambahKeKeranjang($produkId)
    {
        $produk = Produk::find($produkId);

        // Jika stok habis, hentikan proses
        if ($produk->stok < 1) {
            session()->flash('error', 'Stok produk habis!');
            return;
        }

        // Cek apakah barang sudah ada di keranjang
        $index = collect($this->keranjang)->search(fn($item) => $item['produk_id'] == $produkId);

        if ($index !== false) {
            // Jika sudah ada, tambah jumlahnya (qty)
            //cek jika stok habis akan menampilkan error
            if ($this->keranjang[$index]['qty'] >= $produk->stok) {
                session()->flash('error', 'Maksimal! Sisa stok ' . $produk->nama_produk . ' hanya ' . $produk->stok);
                return; // Hentikan penambahan
            }

            $this->keranjang[$index]['qty']++;

        } else {
            // Jika belum ada, masukkan sebagai barang baru
            $this->keranjang[] = [
                'produk_id' => $produk->id,
                'nama' => $produk->nama_produk,
                'harga' => $this->getHargaAktif($produk),
                'qty' => 1,
                'stok_asli' => $produk->stok
            ];
        }

        $this->hitungTotal();
    }

    // Fungsi untuk menghapus 1 baris barang dari keranjang
    public function hapusDariKeranjang($index)
    {
        unset($this->keranjang[$index]);
        $this->keranjang = array_values($this->keranjang); // Reset urutan index array
        $this->hitungTotal();
    }

    // Menentukan harga berdasarkan tipe (Retail / Grosir)
    private function getHargaAktif($produk)
    {
        if ($this->tipe_harga == 'grosir')
            return $produk->harga_grosir;
        if ($this->tipe_harga == 'semi_grosir')
            return $produk->harga_semi_grosir;
        return $produk->harga_retail;
    }

    // Fungsi menghitung ulang semua total dan kembalian
    public function hitungTotal()
    {
        $this->total_harga = 0;

        foreach ($this->keranjang as $key => $item) {
            // Update harga jika kasir tiba-tiba mengganti Tipe Harga di tengah jalan
            $produk = Produk::find($item['produk_id']);
            $this->keranjang[$key]['harga'] = $this->getHargaAktif($produk);

            // Hitung subtotal per baris
            $this->total_harga += $this->keranjang[$key]['harga'] * $item['qty'];
        }

        // Hitung kembalian secara real-time
        $this->kembalian = (int) $this->bayar - $this->total_harga;
    }

    //simpan transaksi
    public function simpanTransaksi()
    {
        if ($this->total_harga == 0 || $this->kembalian < 0 || empty($this->pembayaran_id)) {
            session()->flash('error', 'Data transaksi belum lengkap!');
            return;
        }

        // FINAL CHECK STOK SEBELUM DISIMPAN KE DATABASE
        foreach ($this->keranjang as $item) {
            $cekProduk = Produk::find($item['produk_id']);

            // Jika produk tiba-tiba dihapus admin, atau jumlah beli melebihi stok yang tersisa
            if (!$cekProduk || $item['qty'] > $cekProduk->stok) {
                session()->flash('error', '⚠️ Gagal! Stok "' . ($cekProduk->nama_produk ?? 'Barang Dihapus') . '" tidak mencukupi. Sisa stok: ' . ($cekProduk->stok ?? 0));
                return; // Hentikan seluruh proses, jangan simpan transaksi!
            }
        }

        // --- MULAI LOGIKA KODE TRANSAKSI OTOMATIS ---
        $tanggalHariIni = now()->format('Ymd');

        // Cari transaksi terakhir di hari ini
        $transaksiTerakhir = Transaksi::whereDate('waktu_transaksi', now()->toDateString())
            ->orderBy('id', 'desc')
            ->first();

        if ($transaksiTerakhir) {
            // Jika sudah ada, ambil 5 digit terakhir lalu tambah 1
            $nomorUrutLama = (int) substr($transaksiTerakhir->kode_transaksi, -5);
            $nomorUrutBaru = $nomorUrutLama + 1;
        } else {
            // Jika ini transaksi pertama hari ini, mulai dari 1
            $nomorUrutBaru = 1;
        }

        // Gabungkan menjadi format TRX-YYYYMMDD-XXXXX
        $kodeTransaksiOtomatis = 'TRX-' . $tanggalHariIni . '-' . str_pad($nomorUrutBaru, 5, '0', STR_PAD_LEFT);

        // 1. Simpan Induk Transaksi
        $transaksi = Transaksi::create([
            'kode_transaksi' => $kodeTransaksiOtomatis,
            'user_id' => auth()->user()->id,
            'pembayaran_id' => $this->pembayaran_id,
            'total_harga' => $this->total_harga,
            'bayar' => $this->bayar,
            'kembalian' => $this->kembalian,
            'waktu_transaksi' => now(),
        ]);

        // 2. Simpan Detail Transaksi & Catat Riwayat Stok
        foreach ($this->keranjang as $item) {
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'produk_id' => $item['produk_id'],
                'jumlah' => $item['qty'],
                'harga_satuan' => $item['harga'],
                'subtotal' => $item['harga'] * $item['qty'],
            ]);

            // Ambil data produk
            $produk = Produk::find($item['produk_id']);
            if ($produk) {
                $stokSebelumnya = $produk->stok;

                // Kurangi stok utama
                $produk->decrement('stok', $item['qty']);

                $produk->refresh();

                // notifikasi stok
                $batasMenipis = 2;
                if ($produk->stok <= $batasMenipis) {
                    // Ambil semua pengguna dengan role admin
                    $semuaUser = User::all();

                    // Kirim notifikasi ke database semua pengguna
                    Notification::send($semuaUser, new StokNotification($produk));
                }

                // CATAT KE TABEL RIWAYAT STOK
                RiwayatStok::create([
                    'produk_id' => $produk->id,
                    'user_id' => auth()->user()->id, // Kasir yang bertugas
                    'tipe' => 'sale',
                    'jumlah' => -$item['qty'], // Minus karena barang keluar
                    'stok_akhir' => $produk->stok,
                    'keterangan' => 'Penjualan: ' . $kodeTransaksiOtomatis
                ]);
            }
        }

        // 3. Simpan URL struk sebelum keranjang dibersihkan
        $urlStruk = route('kasir.transaksi.cetak', $transaksi->id);

        // 4. Bersihkan Layar
        $this->reset(['keranjang', 'total_harga', 'bayar', 'kembalian', 'pembayaran_id', 'search', 'tipe_harga']);
        $this->tipe_harga = 'retail';

        // 5. Tampilkan Pesan Sukses
        session()->flash('success', 'Transaksi Berhasil! Mencetak struk...');

        // 6. PERINTAH AJAIB: Memicu browser untuk membuka tab struk
        $this->dispatch('buka-struk', url: $urlStruk);
    }

    public function render()
    {
        $produks = Produk::where('nama_produk', 'like', '%' . $this->search . '%')
            ->orWhere('sku', 'like', '%' . $this->search . '%')
            ->latest()
            ->limit(12)
            ->get();


        $metodePembayaran = Pembayaran::all();

        return view('livewire.kasir-transaksi', [
            'produks' => $produks,
            'metodePembayaran' => $metodePembayaran
        ])->layout('layouts.kasir', ['hideSidebar' => true]);
    }
}