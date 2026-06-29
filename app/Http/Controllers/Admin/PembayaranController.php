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
            'nama_pembayaran' => 'required|string|max:255|unique:pembayarans,nama_pembayaran',
        ]);

        Pembayaran::create($validate);

        return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran berhasil ditambahkan');
    }

    public function update(Request $request, $id){
        $validate = $request->validate([
            'nama_pembayaran' => 'required|string|max:255|unique:pembayarans,nama_pembayaran,' . $id,
        ]);

        Pembayaran::find($id)->update($validate);

        return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran berhasil diupdate');
    }

    public function destroy($id){
        $pembayaran = Pembayaran::find($id);

        // Cek apakah metode pembayaran sudah digunakan di transaksi
        if ($pembayaran->transaksi()->exists()) {
            return redirect()->route('admin.pembayaran.index')
                ->with('error', 'Metode pembayaran "' . $pembayaran->nama_pembayaran . '" tidak dapat dihapus karena sudah digunakan pada riwayat transaksi.');
        }

        $pembayaran->delete();

        return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran berhasil dihapus');
    }
}
