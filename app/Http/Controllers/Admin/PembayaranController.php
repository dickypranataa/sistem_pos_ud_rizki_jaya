<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;

class PembayaranController extends Controller
{
    //
    public function index(){
        $pembayarans = Pembayaran::query()->paginate(10);
        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    public function store(Request $request){
        $validate = $request->validate([
            'nama_pembayaran' => 'required|string|max:255',
        ]);

        Pembayaran::create($validate);

        return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran berhasil ditambahkan');
    }

    public function update(Request $request, $id){
        $validate = $request->validate([
            'nama_pembayaran' => 'required|string|max:255',
        ]);

        Pembayaran::find($id)->update($validate);

        return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran berhasil diupdate');
    }

    public function destroy($id){
        $pembayarans = Pembayaran::find($id);
        $pembayarans->delete();

        return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran berhasil dihapus');
    }
}
