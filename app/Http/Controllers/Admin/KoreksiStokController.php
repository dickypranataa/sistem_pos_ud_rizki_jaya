<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\RiwayatStok;

class KoreksiStokController extends Controller
{
    //
    public function create()
    {
        $produks = Produk::orderBy('nama_produk', 'asc')->get();
        return view('admin.koreksi.create', compact('produks'));
    }

    public function store(Request $request)
    {
       $request->validate([
            'produk_id' => 'required',
            'jenis_koreksi' => 'required|in:restock,sale,correction_plus,correction_minus',
            'jumlah' => 'required|numeric|min:1',
            'keterangan' => 'required|string|max:255',
        ], [
            'produk_id.required' => 'Pilih produk terlebih dahulu.',
            'jumlah.required' => 'Jumlah barang harus diisi.',
            'keterangan.required' => 'Alasan koreksi wajib diisi.',
        ]);

        $produk = Produk::findOrFail($request->produk_id);
        $stokSebelumnya = $produk->stok;

        $jenis = $request->jenis_koreksi;
        $jumlah = $request->jumlah;

        $isPenambahan = false;
        $tipeDatabase = 'correction'; // Default

        if ($jenis === 'restock') {
            $isPenambahan = true;
            $tipeDatabase = 'restock';
        } elseif ($jenis === 'sale') {
            $isPenambahan = false;
            $tipeDatabase = 'sale';
        } elseif ($jenis === 'correction_plus') {
            $isPenambahan = true;
            $tipeDatabase = 'correction';
        } elseif ($jenis === 'correction_minus') {
            $isPenambahan = false;
            $tipeDatabase = 'correction';
        }

        // 3. Eksekusi Perubahan Stok Utama
        if ($isPenambahan) {
            $produk->increment('stok', $jumlah);
            $angkaRiwayat = $jumlah;
            $keterangan = $request->keterangan;
        } else {
            // BLOKIR JIKA PENGURANGAN MELEBIHI STOK
            if ($produk->stok < $jumlah) {
                return back()->with('error', "Koreksi gagal: Jumlah pengurangan ($jumlah) melebihi sisa stok saat ini ({$produk->stok}).")->withInput();
            }
            
            $produk->decrement('stok', $jumlah);
            $angkaRiwayat = -$jumlah;
            $keterangan = $request->keterangan;
        }

        // 4. Catat ke Tabel Riwayat Stok sesuai tipe aslinya
        RiwayatStok::create([
            'produk_id'  => $produk->id,
            'user_id'    => auth()->user()->id,
            'tipe'       => $tipeDatabase,
            'jumlah'     => $angkaRiwayat,
            'stok_akhir' => $stokSebelumnya + $angkaRiwayat,
            'keterangan' => $keterangan
        ]);

        return back()->with('success', 'Koreksi stok berhasil dicatat!');
    }
}
