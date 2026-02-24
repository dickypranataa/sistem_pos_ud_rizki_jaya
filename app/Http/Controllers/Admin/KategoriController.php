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
        $validate = $request->validate([
            'nama_kategori' => 'required|unique:kategoris,nama_kategori',
        ]);

        Kategori::create($validate);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function update(Request $request, $id){
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);
        Kategori::find($id)->update($request->all());
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy($id){
        $kategori = Kategori::find($id);
        $kategori->delete();

        return redirect()->route('admin.kategori.index');
    }

}
