<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\RiwayatStok;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index(){
        $search     = request('search');
        $kategoriId = request('kategori_id');

        $query = Produk::with('kategori')->latest();

        // Filter pencarian SKU / Nama Produk
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan kategori (dropdown)
        if ($kategoriId) {
            $query->where('kategori_id', $kategoriId);
        }

        $produks   = $query->paginate(10)->withQueryString();
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('admin.produk.index', compact('produks', 'kategoris'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.produk.create', compact('kategoris'));
    }

    public function store(Request $request){
        $request->validate([
            'kategori_id'       => 'required|integer|exists:kategoris,id',
            'sku'               => 'string|required|unique:produks,sku',
            'nama_produk'       => 'string|required|max:255',
            'satuan'            => 'string|required|max:255',
            'stok'              => 'integer|required|min:0',
            'harga_modal'       => 'required|decimal:0,2|min:0',
            'harga_retail'      => 'required|decimal:0,2|min:0',
            'harga_semi_grosir' => 'required|decimal:0,2|min:0',
            'harga_grosir'      => 'required|decimal:0,2|min:0',
            'gambar'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'stok.min'              => 'Stok tidak boleh bernilai minus. Masukkan angka 0 atau lebih.',
            'stok.integer'          => 'Stok harus berupa bilangan bulat (tidak boleh desimal).',
            'stok.required'         => 'Stok wajib diisi.',
            'harga_modal.min'       => 'Harga modal tidak boleh bernilai minus.',
            'harga_retail.min'      => 'Harga retail tidak boleh bernilai minus.',
            'harga_semi_grosir.min' => 'Harga semi grosir tidak boleh bernilai minus.',
            'harga_grosir.min'      => 'Harga grosir tidak boleh bernilai minus.',
            'sku.unique'            => 'SKU sudah digunakan oleh produk lain.',
            'kategori_id.exists'    => 'Kategori yang dipilih tidak valid.',
            'gambar.max'            => 'Ukuran gambar maksimal 2MB.',
            'gambar.mimes'          => 'Format gambar harus JPG, PNG, JPEG, atau GIF.',
        ]);

        $data = $request->except('gambar');

        // Sanitasi stok: pastikan tidak tersimpan sebagai -0 atau nilai negatif apapun
        $data['stok'] = abs((int) $request->stok);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        Produk::create($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id){
        $produks = Produk::find($id);
        $kategoris = Kategori::all();
        return view('admin.produk.edit', compact('produks', 'kategoris'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'kategori_id'       => 'required|integer|exists:kategoris,id',
            'sku'               => 'string|required|unique:produks,sku,' . $id,
            'nama_produk'       => 'string|required|max:255',
            'satuan'            => 'string|required|max:255',
            'stok'              => 'integer|required|min:0',
            'harga_modal'       => 'required|decimal:0,2|min:0',
            'harga_retail'      => 'required|decimal:0,2|min:0',
            'harga_semi_grosir' => 'required|decimal:0,2|min:0',
            'harga_grosir'      => 'required|decimal:0,2|min:0',
            'gambar'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'stok.min'              => 'Stok tidak boleh bernilai minus. Masukkan angka 0 atau lebih.',
            'stok.integer'          => 'Stok harus berupa bilangan bulat (tidak boleh desimal).',
            'stok.required'         => 'Stok wajib diisi.',
            'harga_modal.min'       => 'Harga modal tidak boleh bernilai minus.',
            'harga_retail.min'      => 'Harga retail tidak boleh bernilai minus.',
            'harga_semi_grosir.min' => 'Harga semi grosir tidak boleh bernilai minus.',
            'harga_grosir.min'      => 'Harga grosir tidak boleh bernilai minus.',
            'sku.unique'            => 'SKU sudah digunakan oleh produk lain.',
            'kategori_id.exists'    => 'Kategori yang dipilih tidak valid.',
            'gambar.max'            => 'Ukuran gambar maksimal 2MB.',
            'gambar.mimes'          => 'Format gambar harus JPG, PNG, JPEG, atau GIF.',
        ]);

        $produk = Produk::findOrFail($id);
        $data   = $request->except('gambar');

        // Sanitasi stok: pastikan tidak tersimpan sebagai -0 atau nilai negatif apapun
        $data['stok'] = abs((int) $request->stok);

        // JIKA ADA GAMBAR BARU YANG DIUNGGAH
        if ($request->hasFile('gambar')) {
            // 1. Hapus gambar lama dari server (jika ada)
            if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
                Storage::disk('public')->delete($produk->gambar);
            }
            // 2. Simpan gambar baru
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diupdate');
    }

    public function destroy($id){
        $produk = Produk::find($id);

        if (!$produk) {
            return redirect()->route('admin.produk.index')
                ->with('error', 'Produk tidak ditemukan.');
        }

        // Cek apakah produk sudah pernah dijual dalam transaksi
        if ($produk->detailTransaksi()->exists()) {
            return redirect()->route('admin.produk.index')
                ->with('error', 'Produk "' . $produk->nama_produk . '" tidak dapat dihapus karena sudah tercatat dalam riwayat transaksi.');
        }

        // Cek apakah produk sudah memiliki riwayat stok
        $jumlahRiwayat = RiwayatStok::where('produk_id', $produk->id)->count();
        if ($jumlahRiwayat > 0) {
            return redirect()->route('admin.produk.index')
                ->with('error', 'Produk "' . $produk->nama_produk . '" tidak dapat dihapus karena memiliki ' . $jumlahRiwayat . ' riwayat stok yang tercatat. Hapus riwayat stok terlebih dahulu atau arsipkan produk ini.');
        }

        // HAPUS GAMBAR FISIK SAAT PRODUK DIHAPUS
        if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus');
    }
}