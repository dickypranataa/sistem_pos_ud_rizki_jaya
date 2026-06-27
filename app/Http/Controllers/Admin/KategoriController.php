<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    //
    public function index(){
        $search = request('search');
        $kategoris = Kategori::query()->where('nama_kategori', 'like', "%{$search}%")
                        ->latest()
                        ->paginate(10)
                        ->withQueryString();

        return view('admin.kategori.index', compact('kategoris'),);
    }


    public function store(Request $request){
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Nama kategori "' . $request->nama_kategori . '" sudah terdaftar. Gunakan nama yang berbeda.',
            'nama_kategori.max'      => 'Nama kategori maksimal 255 karakter.',
        ]);

        Kategori::create($request->only('nama_kategori'));

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan');
    }


    public function update(Request $request, $id){
        $kategori = Kategori::findOrFail($id);

        // Simpan data ke session agar modal edit bisa dibuka ulang bila validasi gagal
        session()->flash('edit_kategori_id',   $id);
        session()->flash('edit_kategori_nama', $request->nama_kategori_edit);

        $request->validate([
            'nama_kategori_edit' => 'required|string|max:255|unique:kategoris,nama_kategori,' . $id,
        ], [
            'nama_kategori_edit.required' => 'Nama kategori wajib diisi.',
            'nama_kategori_edit.unique'   => 'Nama kategori "' . $request->nama_kategori_edit . '" sudah terdaftar. Gunakan nama yang berbeda.',
            'nama_kategori_edit.max'      => 'Nama kategori maksimal 255 karakter.',
        ]);

        $kategori->update(['nama_kategori' => $request->nama_kategori_edit]);
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy($id){
        $kategori = Kategori::findOrFail($id);

        if ($kategori->produk()->exists()) {
            return redirect()->route('admin.kategori.index')->with('error', 'Kategori "' . $kategori->nama_kategori . '" tidak dapat dihapus karena masih digunakan oleh produk lain.');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus');
    }

}
