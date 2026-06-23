<?php

namespace App\Livewire;

use App\Models\DetailTransaksi;
use App\Models\Kategori;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Piutang;
use App\Models\Produk;
use App\Models\RiwayatStok;
use App\Models\Transaksi;
use App\Models\User;
use App\Notifications\StokNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class KasirTransaksi extends Component
{
    // === State Keranjang & Pembayaran ===
    public $search = '';

    public $selectedKategori = '';

    public $keranjang = [];

    public $total_harga = 0;

    public $bayar = 0;

    public $kembalian = 0;

    public $tipe_harga = 'retail';

    public $pembayaran_id = '';

    // === State Piutang ===
    public $isPiutang = false;

    public $pelanggan_search = '';

    public $pelanggan_id = null;

    public $pelanggan_nama = '';   // nama pelanggan terpilih (untuk tampilan)

    public $show_form_baru = false; // toggle form tambah pelanggan baru

    public $pelanggan_baru_nama = '';

    public $pelanggan_baru_alamat = '';

    public $pelanggan_baru_no_hp = '';

    public $dp = 0;

    public $jatuh_tempo = '';

    public $hasil_cari_pelanggan = [];

    public function mount()
    {
        // Default jatuh tempo 14 hari dari sekarang
        $this->jatuh_tempo = now()->addDays(14)->format('Y-m-d');
    }

    // Dipanggil setiap kali properti berubah
    public function updated($property)
    {
        if ($property === 'bayar' || $property === 'tipe_harga' || str_starts_with($property, 'keranjang')) {
            $this->hitungTotal();
        }
        // Reset piutang saat pembayaran diganti
        if ($property === 'pembayaran_id') {
            $this->detectPiutang();
        }
        // Live search pelanggan
        if ($property === 'pelanggan_search') {
            $this->cariPelanggan();
        }
    }

    // Deteksi apakah metode pembayaran yang dipilih adalah "Piutang / Bon"
    private function detectPiutang()
    {
        if (empty($this->pembayaran_id)) {
            $this->isPiutang = false;

            return;
        }
        $pembayaran = Pembayaran::find($this->pembayaran_id);
        $this->isPiutang = $pembayaran && str_contains(strtolower($pembayaran->nama_pembayaran), 'piutang');

        if (! $this->isPiutang) {
            $this->resetPiutangState();
        }
    }

    // Reset semua state piutang
    public function resetPiutangState()
    {
        $this->pelanggan_search = '';
        $this->pelanggan_id = null;
        $this->pelanggan_nama = '';
        $this->show_form_baru = false;
        $this->pelanggan_baru_nama = '';
        $this->pelanggan_baru_alamat = '';
        $this->pelanggan_baru_no_hp = '';
        $this->dp = 0;
        $this->hasil_cari_pelanggan = [];
        $this->jatuh_tempo = now()->addDays(14)->format('Y-m-d');
    }

    // Reset filter pencarian & kategori — dipanggil dari tombol Reset di view
    public function resetFilter()
    {
        $this->search          = '';
        $this->selectedKategori = '';

        // Dispatch event ke browser agar input DOM ikut di-reset (mencegah bug morphdom)
        $this->dispatch('reset-filter-inputs');
    }

    // AJAX search pelanggan
    public function cariPelanggan()
    {
        if (strlen($this->pelanggan_search) < 2) {
            $this->hasil_cari_pelanggan = [];

            return;
        }
        $this->hasil_cari_pelanggan = Pelanggan::where('nama_pelanggan', 'like', '%'.$this->pelanggan_search.'%')
            ->orWhere('no_hp', 'like', '%'.$this->pelanggan_search.'%')
            ->limit(6)
            ->get(['id', 'nama_pelanggan', 'alamat', 'no_hp'])
            ->toArray();
    }

    // Pilih pelanggan dari hasil pencarian
    public function pilihPelanggan($id, $nama)
    {
        $this->pelanggan_id = $id;
        $this->pelanggan_nama = $nama;
        $this->pelanggan_search = $nama;
        $this->hasil_cari_pelanggan = [];
        $this->show_form_baru = false;
    }

    // Batal pilih pelanggan
    public function batalPilihPelanggan()
    {
        $this->pelanggan_id = null;
        $this->pelanggan_nama = '';
        $this->pelanggan_search = '';
        $this->hasil_cari_pelanggan = [];
    }

    // Toggle form pelanggan baru
    public function toggleFormBaru()
    {
        $this->show_form_baru = ! $this->show_form_baru;
        if ($this->show_form_baru) {
            $this->pelanggan_id = null;
            $this->pelanggan_nama = '';
            $this->pelanggan_search = '';
            $this->hasil_cari_pelanggan = [];
        }
    }

    // Memasukkan barang ke keranjang
    public function tambahKeKeranjang($produkId)
    {
        $produk = Produk::find($produkId);
        if ($produk->stok < 1) {
            session()->flash('error', 'Stok produk habis!');

            return;
        }
        $index = collect($this->keranjang)->search(fn ($item) => $item['produk_id'] == $produkId);
        if ($index !== false) {
            if ($this->keranjang[$index]['qty'] >= $produk->stok) {
                session()->flash('error', 'Maksimal! Sisa stok '.$produk->nama_produk.' hanya '.$produk->stok);

                return;
            }
            $this->keranjang[$index]['qty']++;
        } else {
            $this->keranjang[] = [
                'produk_id' => $produk->id,
                'nama' => $produk->nama_produk,
                'harga' => $this->getHargaAktif($produk),
                'qty' => 1,
                'stok_asli' => $produk->stok,
            ];
        }
        $this->hitungTotal();
    }

    // Hapus barang dari keranjang
    public function hapusDariKeranjang($index)
    {
        unset($this->keranjang[$index]);
        $this->keranjang = array_values($this->keranjang);
        $this->hitungTotal();
    }

    // Harga aktif berdasarkan tipe
    private function getHargaAktif($produk)
    {
        if ($this->tipe_harga == 'grosir') {
            return $produk->harga_grosir;
        }
        if ($this->tipe_harga == 'semi_grosir') {
            return $produk->harga_semi_grosir;
        }

        return $produk->harga_retail;
    }

    // Hitung total & kembalian
    public function hitungTotal()
    {
        $this->total_harga = 0;
        $itemsToRemoval = [];

        if (! empty($this->keranjang)) {
            $productIds = collect($this->keranjang)->pluck('produk_id')->toArray();
            $dbProduks = Produk::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($this->keranjang as $key => $item) {
                $qty = $item['qty'];

                if ($qty === 0 || $qty === '0') {
                    $itemsToRemoval[] = $key;

                    continue;
                }

                $qtyInt = (int) $qty;

                if ($qty === null || $qty === '') {
                    $qtyInt = 1;
                    $this->keranjang[$key]['qty'] = 1;
                } elseif ($qtyInt < 0) {
                    $this->keranjang[$key]['qty'] = $qtyInt;
                    session()->flash('error', 'Jumlah barang untuk "'.$item['nama'].'" tidak boleh minus!');
                } elseif ($qtyInt > $item['stok_asli']) {
                    $qtyInt = (int) $item['stok_asli'];
                    $this->keranjang[$key]['qty'] = $qtyInt;
                    session()->flash('error', 'Stok terbatas! Maksimal pembelian '.$item['nama'].' adalah '.$qtyInt);
                }

                $produk = $dbProduks->get($item['produk_id']);
                if ($produk) {
                    $hargaAktif = (int) $this->getHargaAktif($produk);
                    $this->keranjang[$key]['harga'] = $hargaAktif;
                    if ($qtyInt > 0) {
                        $this->total_harga += $hargaAktif * $qtyInt;
                    }
                }
            }

            if (! empty($itemsToRemoval)) {
                foreach ($itemsToRemoval as $key) {
                    unset($this->keranjang[$key]);
                }
                $this->keranjang = array_values($this->keranjang);
            }
        }

        $bayarBersih = (int) str_replace('.', '', $this->bayar);
        $this->kembalian = $bayarBersih - $this->total_harga;
    }

    // Cek qty tidak valid
    public function hasInvalidQty()
    {
        return collect($this->keranjang)->contains(fn ($item) => (int) $item['qty'] < 1);
    }

    // Simpan Transaksi
    public function simpanTransaksi()
    {
        // Validasi qty
        foreach ($this->keranjang as $item) {
            if ((int) $item['qty'] < 1) {
                session()->flash('error', 'Gagal! Terdapat barang dengan jumlah tidak valid (kurang dari 1).');

                return;
            }
        }

        // Validasi dasar
        if ($this->total_harga == 0 || empty($this->pembayaran_id)) {
            session()->flash('error', 'Data transaksi belum lengkap!');

            return;
        }

        // Validasi khusus piutang
        if ($this->isPiutang) {
            // Harus ada pelanggan
            if (empty($this->pelanggan_id) && empty($this->pelanggan_baru_nama)) {
                session()->flash('error', 'Piutang harus memilih atau menambah data pelanggan!');

                return;
            }
            if (empty($this->jatuh_tempo)) {
                session()->flash('error', 'Tanggal jatuh tempo harus diisi untuk transaksi piutang!');

                return;
            }
            $dp = (int) str_replace('.', '', $this->dp);
            if ($dp < 0 || $dp > $this->total_harga) {
                session()->flash('error', 'Uang muka (DP) tidak valid!');

                return;
            }
        } else {
            // Transaksi tunai: validasi kembalian
            if ($this->kembalian < 0) {
                session()->flash('error', 'Uang yang diterima kurang dari total harga!');

                return;
            }
        }

        try {
            $transaksiBerhasil = DB::transaction(function () {
                // Auto-increment kode transaksi per hari
                $transaksiTerakhir = Transaksi::whereDate('waktu_transaksi', now()->toDateString())
                    ->lockForUpdate()
                    ->orderBy('id', 'desc')
                    ->first();
                $nomorUrut = $transaksiTerakhir ? ((int) substr($transaksiTerakhir->kode_transaksi, -5) + 1) : 1;
                $kode = 'TRX-'.now()->format('Ymd').'-'.str_pad($nomorUrut, 5, '0', STR_PAD_LEFT);

                $bayarBersih = $this->isPiutang ? 0 : (int) str_replace('.', '', $this->bayar);
                $kembalianBersih = $this->isPiutang ? 0 : $this->kembalian;

                // 1. Simpan Transaksi Induk
                $transaksi = Transaksi::create([
                    'kode_transaksi' => $kode,
                    'user_id' => auth()->user()->id,
                    'pembayaran_id' => $this->pembayaran_id,
                    'tipe_harga' => $this->tipe_harga,
                    'total_harga' => $this->total_harga,
                    'bayar' => $bayarBersih,
                    'kembalian' => $kembalianBersih,
                    'waktu_transaksi' => now(),
                ]);

                // 2. Simpan Detail & Update Stok
                $productIds = collect($this->keranjang)->pluck('produk_id')->toArray();
                $dbProduks = Produk::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

                foreach ($this->keranjang as $item) {
                    $produk = $dbProduks->get($item['produk_id']);
                    if (! $produk || $item['qty'] > $produk->stok) {
                        throw new \Exception('Gagal! Stok "'.($produk->nama_produk ?? 'Barang').'" tidak mencukupi.');
                    }
                    $hargaAkurat = (int) $this->getHargaAktif($produk);
                    DetailTransaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'produk_id' => $produk->id,
                        'jumlah' => $item['qty'],
                        'harga_satuan' => $hargaAkurat,
                        'subtotal' => $hargaAkurat * $item['qty'],
                    ]);
                    $produk->decrement('stok', $item['qty']);
                    if ($produk->stok <= 2) {
                        Notification::send(User::all(), new StokNotification($produk));
                    }
                    RiwayatStok::create([
                        'produk_id' => $produk->id,
                        'user_id' => auth()->user()->id,
                        'tipe' => 'sale',
                        'jumlah' => -$item['qty'],
                        'stok_akhir' => $produk->stok,
                        'keterangan' => 'Penjualan: '.$kode,
                    ]);
                }

                // 3. Jika Piutang, simpan ke tabel piutangs
                if ($this->isPiutang) {
                    $dp = (int) str_replace('.', '', $this->dp);

                    // Buat atau gunakan pelanggan yang ada
                    if (! empty($this->pelanggan_baru_nama) && empty($this->pelanggan_id)) {
                        $pelanggan = Pelanggan::create([
                            'nama_pelanggan' => trim($this->pelanggan_baru_nama),
                            'alamat' => trim($this->pelanggan_baru_alamat),
                            'no_hp' => trim($this->pelanggan_baru_no_hp),
                        ]);
                        $pelangganId = $pelanggan->id;
                    } else {
                        $pelangganId = $this->pelanggan_id;
                    }

                    $sisaTagihan = $this->total_harga - $dp;

                    Piutang::create([
                        'transaksi_id' => $transaksi->id,
                        'pelanggan_id' => $pelangganId,
                        'sisa_tagihan' => $sisaTagihan,
                        'status' => $sisaTagihan <= 0 ? 'lunas' : 'belum_lunas',
                        'jatuh_tempo' => $this->jatuh_tempo,
                    ]);
                }

                return $transaksi;
            });

            $urlStruk = route('kasir.transaksi.cetak', $transaksiBerhasil->id);

            $this->reset(['keranjang', 'total_harga', 'bayar', 'kembalian', 'pembayaran_id',
                'search', 'selectedKategori', 'tipe_harga', 'isPiutang', 'pelanggan_id', 'pelanggan_nama',
                'pelanggan_search', 'dp', 'show_form_baru',
                'pelanggan_baru_nama', 'pelanggan_baru_alamat', 'pelanggan_baru_no_hp',
                'hasil_cari_pelanggan']);
            $this->tipe_harga = 'retail';
            $this->jatuh_tempo = now()->addDays(14)->format('Y-m-d');

            session()->flash('success', 'Transaksi Berhasil! Mencetak struk...');
            $this->dispatch('buka-struk', url: $urlStruk);
            $this->js('setTimeout(() => { window.location.reload(); }, 1500);');

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        $query = Produk::with('kategori');

        // Filter berdasarkan kategori (dropdown)
        if (! empty($this->selectedKategori)) {
            $query->where('kategori_id', $this->selectedKategori);
        }

        // Filter berdasarkan pencarian nama / SKU
        if (! empty($this->search)) {
            $term = '%'.$this->search.'%';
            $query->where(function ($q) use ($term) {
                $q->where('nama_produk', 'like', $term)
                  ->orWhere('sku', 'like', $term);
            });
        }

        $produks          = $query->latest()->limit(24)->get();
        $kategoris        = Kategori::orderBy('nama_kategori')->get();
        $metodePembayaran = Pembayaran::all();

        return view('livewire.kasir-transaksi', [
            'produks'          => $produks,
            'kategoris'        => $kategoris,
            'metodePembayaran' => $metodePembayaran,
        ])->layout('layouts.kasir', [
            'hideSidebar'  => true,
            'hideNavbar'   => true,
            'hideFooter'   => true,
            'isFullScreen' => true,
        ]);
    }
}
