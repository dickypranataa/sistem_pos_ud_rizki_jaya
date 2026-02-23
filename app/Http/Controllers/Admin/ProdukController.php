<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;

class ProdukController extends Controller
{
    //
    public function index(){
        $search = request('search');
        $produks = Produk::query()->where('nama_produk', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%")
                      ->latest()
                      ->paginate(10)
                      ->withQueryString();


        return view('admin.produk.index', compact('produks'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.produk.create', compact('kategoris'));
    }

    public function store(Request $request){
        $request->validate([
            'kategori_id' => 'required|integer|exists:kategoris,id',
            'sku' => 'string|required|unique:produks,sku',
            'nama_produk' => 'string|required|max:255',
            'satuan' => 'string|required|max:255',
            'stok' => 'integer|required|min:0',
            'harga_modal'       => 'required|decimal:0,2|min:0',
            'harga_retail'      => 'required|decimal:0,2|min:0',
            'harga_semi_grosir' => 'required|decimal:0,2|min:0',
            'harga_grosir'      => 'required|decimal:0,2|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        //validasi gambar
        if($request->hasFile('gambar')){
            $data['gambar'] = $request->file('gambar')->store('produk','public');
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
            'kategori_id' => 'required|integer|exists:kategoris,id',
            'sku' => 'string|required|unique:produks,sku,' . $id,
            'nama_produk' => 'string|required|max:255',
            'satuan' => 'string|required|max:255',
            'stok' => 'integer|required|min:0',
            'harga_modal'       => 'required|decimal:0,2|min:0',
            'harga_retail'      => 'required|decimal:0,2|min:0',
            'harga_semi_grosir' => 'required|decimal:0,2|min:0',
            'harga_grosir'      => 'required|decimal:0,2|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        //validasi gambar
        if($request->hasFile('gambar')){
            $data['gambar'] = $request->file('gambar')->store('produk','public');
        }

        Produk::find($id)->update($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diupdate');
    }

    public function destroy($id){
        Produk::find($id)->delete();
        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus');
    }


}
