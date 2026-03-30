<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;

class RiwayatStokController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');

        $riwayats = RiwayatStok::with(['produk', 'user'])
            // Filter berdasarkan nama produk
            ->when($search, function ($query) use ($search) {
                $query->whereHas('produk', function ($q) use ($search) {
                    $q->where('nama_produk', 'like', "%{$search}%");
                });
            })
            // Filter jika kedua tanggal diisi
            ->when($tanggal_awal && $tanggal_akhir, function ($query) use ($tanggal_awal, $tanggal_akhir) {
                // Tambahkan waktu agar mencakup satu hari penuh hingga jam 23:59:59
                $query->whereBetween('created_at', [$tanggal_awal . ' 00:00:00', $tanggal_akhir . ' 23:59:59']);
            })
            // Filter jika hanya tanggal awal yang diisi
            ->when($tanggal_awal && !$tanggal_akhir, function ($query) use ($tanggal_awal) {
                $query->whereDate('created_at', '>=', $tanggal_awal);
            })
            // Filter jika hanya tanggal akhir yang diisi
            ->when(!$tanggal_awal && $tanggal_akhir, function ($query) use ($tanggal_akhir) {
                $query->whereDate('created_at', '<=', $tanggal_akhir);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.riwayat.index', compact('riwayats'));
    }
}