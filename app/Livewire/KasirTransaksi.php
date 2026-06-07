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
use Illuminate\Support\Facades\DB;
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
        $itemsToRemoval = [];

        if (!empty($this->keranjang)) {
            $productIds = collect($this->keranjang)->pluck('produk_id')->toArray();
            $dbProduks = Produk::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($this->keranjang as $key => $item) {
                $qty = $item['qty'];

                // 1. Jika qty diubah menjadi 0 atau '0', tandai untuk dihapus dari keranjang secara otomatis
                if ($qty === 0 || $qty === '0') {
                    $itemsToRemoval[] = $key;
                    continue;
                }

                $qtyInt = (int)$qty;

                // 2. Jika input kosong (null atau ""), default ke 1
                if ($qty === null || $qty === '') {
                    $qtyInt = 1;
                    $this->keranjang[$key]['qty'] = 1;
                }
                // 3. Jika bernilai negatif (kurang dari 0), tampilkan error tapi biarkan nilai tetap minus agar kasir tahu
                elseif ($qtyInt < 0) {
                    $this->keranjang[$key]['qty'] = $qtyInt; // tetap pertahankan nilai negatif di input
                    session()->flash('error', 'Jumlah barang untuk "' . $item['nama'] . '" tidak boleh minus!');
                }
                // 4. Jika melebihi stok asli
                elseif ($qtyInt > $item['stok_asli']) {
                    $qtyInt = (int)$item['stok_asli'];
                    $this->keranjang[$key]['qty'] = $qtyInt;
                    session()->flash('error', 'Stok terbatas! Maksimal pembelian ' . $item['nama'] . ' adalah ' . $qtyInt);
                }

                // Ambil dari memori (Collection), BUKAN query ke database lagi
                $produk = $dbProduks->get($item['produk_id']);

                if ($produk) {
                    $hargaAktif = (int) $this->getHargaAktif($produk);
                    $this->keranjang[$key]['harga'] = $hargaAktif;
                    
                    // Hanya tambahkan ke total jika qty positif
                    if ($qtyInt > 0) {
                        $this->total_harga += $hargaAktif * $qtyInt;
                    }
                }
            }

            // Hapus item yang qty-nya 0
            if (!empty($itemsToRemoval)) {
                foreach ($itemsToRemoval as $key) {
                    unset($this->keranjang[$key]);
                }
                $this->keranjang = array_values($this->keranjang); // Reset indeks array agar berurutan
            }
        }

        // Hitung kembalian secara real-time
        // Menggunakan str_replace untuk membersihkan titik (jika ada input dari Alpine.js)
        $bayarBersih = (int) str_replace('.', '', $this->bayar);
        $this->kembalian = $bayarBersih - $this->total_harga;
    }

    //simpan transaksi
    public function simpanTransaksi()
    {
        // Validasi jika ada item minus/tidak valid di keranjang
        foreach ($this->keranjang as $item) {
            if ((int)$item['qty'] < 1) {
                session()->flash('error', 'Gagal memproses transaksi! Terdapat barang dengan jumlah tidak valid (kurang dari 1).');
                return;
            }
        }

        if ($this->total_harga == 0 || $this->kembalian < 0 || empty($this->pembayaran_id)) {
            session()->flash('error', 'Data transaksi belum lengkap!');
            return;
        }

        try {
            // Gunakan DB Transaction agar aman dari bentrok kasir lain (Race Condition)
            $transaksiBerhasil = DB::transaction(function () {

                // Cari transaksi terakhir dan KUNCI baris tersebut saat dibaca
                $transaksiTerakhir = Transaksi::whereDate('waktu_transaksi', now()->toDateString())
                    ->lockForUpdate() // <-- Kunci penting agar tidak ada nomor struk ganda
                    ->orderBy('id', 'desc')
                    ->first();

                $nomorUrutBaru = $transaksiTerakhir ? ((int) substr($transaksiTerakhir->kode_transaksi, -5) + 1) : 1;
                $kodeTransaksiOtomatis = 'TRX-' . now()->format('Ymd') . '-' . str_pad($nomorUrutBaru, 5, '0', STR_PAD_LEFT);

                // Bersihkan nilai bayar dari titik (jika ada input Alpine)
                $bayarBersih = (int) str_replace('.', '', $this->bayar);

                // 1. Simpan Induk Transaksi
                $transaksi = Transaksi::create([
                    'kode_transaksi' => $kodeTransaksiOtomatis,
                    'user_id' => auth()->user()->id,
                    'pembayaran_id' => $this->pembayaran_id,
                    'tipe_harga' => $this->tipe_harga, // [FIX BUG #1] Simpan tipe harga pelanggan
                    'total_harga' => $this->total_harga,
                    'bayar' => $bayarBersih,
                    'kembalian' => $this->kembalian,
                    'waktu_transaksi' => now(),
                ]);

                // Ambil ulang produk dari Database dan kunci datanya (lockForUpdate)
                $productIds = collect($this->keranjang)->pluck('produk_id')->toArray();
                $dbProduks = Produk::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

                // 2. Simpan Detail Transaksi & Catat Riwayat Stok
                foreach ($this->keranjang as $item) {
                    $produk = $dbProduks->get($item['produk_id']);

                    // Jika di tengah jalan tiba-tiba barang dihapus admin atau stok kurang
                    if (!$produk || $item['qty'] > $produk->stok) {
                        throw new \Exception('Gagal! Stok "' . ($produk->nama_produk ?? 'Barang') . '" tidak mencukupi.');
                    }

                    // JANGAN pakai $item['harga']. Ambil ulang harga ASLI dari Database!
                    $hargaAkurat = (int) $this->getHargaAktif($produk);

                    DetailTransaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'produk_id' => $produk->id,
                        'jumlah' => $item['qty'],
                        'harga_satuan' => $hargaAkurat, // Aman dari manipulasi hacker
                        'subtotal' => $hargaAkurat * $item['qty'],
                    ]);

                    // Kurangi stok utama
                    $produk->decrement('stok', $item['qty']);

                    // Notifikasi stok menipis
                    if ($produk->stok <= 2) {
                        $semuaUser = User::all();
                        Notification::send($semuaUser, new StokNotification($produk));
                    }

                    // CATAT KE TABEL RIWAYAT STOK
                    RiwayatStok::create([
                        'produk_id' => $produk->id,
                        'user_id' => auth()->user()->id,
                        'tipe' => 'sale',
                        'jumlah' => -$item['qty'],
                        'stok_akhir' => $produk->stok,
                        'keterangan' => 'Penjualan: ' . $kodeTransaksiOtomatis
                    ]);
                }

                // Kembalikan objek transaksi jika DB transaction berhasil
                return $transaksi;
            });

            // --- JIKA BERHASIL (Keluar dari blok DB Transaction) ---

            $urlStruk = route('kasir.transaksi.cetak', $transaksiBerhasil->id);

            // Bersihkan Layar
            $this->reset(['keranjang', 'total_harga', 'bayar', 'kembalian', 'pembayaran_id', 'search', 'tipe_harga']);
            $this->tipe_harga = 'retail';

            session()->flash('success', 'Transaksi Berhasil! Mencetak struk...');

            $this->dispatch('buka-struk', url: $urlStruk);
            $this->js('setTimeout(() => { window.location.reload(); }, 1500);');

        } catch (\Exception $e) {
            // Jika ada error (stok habis, db mati, dll), batalkan SEMUA penyimpanan dan tampilkan error
            session()->flash('error', $e->getMessage());
        }
    }

    // Cek apakah ada barang di keranjang yang memiliki jumlah tidak valid (kurang dari 1)
    public function hasInvalidQty()
    {
        return collect($this->keranjang)->contains(fn($item) => (int)$item['qty'] < 1);
    }

    public function render()
    {
        // Tambahkan with('kategori') agar query lebih ringan
        $produks = Produk::with('kategori')
            ->where('nama_produk', 'like', '%' . $this->search . '%')
            ->orWhere('sku', 'like', '%' . $this->search . '%')
            ->latest()
            ->limit(12)
            ->get();

        $metodePembayaran = Pembayaran::all();

        return view('livewire.kasir-transaksi', [
            'produks' => $produks,
            'metodePembayaran' => $metodePembayaran
        ])->layout('layouts.kasir', [
                    'hideSidebar' => true,
                    'hideNavbar' => true,
                    'hideFooter' => true,
                    'isFullScreen' => true,
                ]);
    }
}