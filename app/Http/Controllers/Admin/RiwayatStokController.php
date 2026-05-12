<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RiwayatStokController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'nullable|date|required_with:tanggal_akhir',
            'tanggal_akhir' => 'nullable|date|required_with:tanggal_awal|after_or_equal:tanggal_awal',
        ], [
            'tanggal_awal.required_with' => 'Dari tanggal wajib diisi jika sampai tanggal diisi.',
            'tanggal_akhir.required_with' => 'Sampai tanggal wajib diisi jika dari tanggal diisi.',
            'tanggal_akhir.after_or_equal' => 'Sampai tanggal tidak boleh lebih kecil dari tanggal awal.'
        ]);

        $search = $request->input('search');
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');

        $riwayats = RiwayatStok::with(['produk', 'user'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('produk', function ($q) use ($search) {
                    $q->where('nama_produk', 'like', "%{$search}%");
                });
            })
            ->when($tanggal_awal && $tanggal_akhir, function ($query) use ($tanggal_awal, $tanggal_akhir) {
                $query->whereBetween('created_at', [$tanggal_awal . ' 00:00:00', $tanggal_akhir . ' 23:59:59']);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.riwayat.index', compact('riwayats'));
    }

    public function exportPDF(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'nullable|date|required_with:tanggal_akhir',
            'tanggal_akhir' => 'nullable|date|required_with:tanggal_awal|after_or_equal:tanggal_awal',
        ]);

        $search = $request->input('search');
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');

        $riwayat = RiwayatStok::with(['produk', 'user'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('produk', function ($q) use ($search) {
                    $q->where('nama_produk', 'like', "%{$search}%");
                });
            })
            ->when($tanggal_awal && $tanggal_akhir, function ($query) use ($tanggal_awal, $tanggal_akhir) {
                $query->whereBetween('created_at', [$tanggal_awal . ' 00:00:00', $tanggal_akhir . ' 23:59:59']);
            })
            ->orderBy('created_at', 'desc')
            ->get(); 

        $namaFile = 'Laporan_Pergerakan_Stok';
        if ($tanggal_awal && $tanggal_akhir) {
            $namaFile .= '_' . $tanggal_awal . '_sd_' . $tanggal_akhir;
        }

        $pdf = Pdf::loadView('admin.riwayat.pdf', compact('riwayat', 'tanggal_awal', 'tanggal_akhir'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download($namaFile . '.pdf');
    }
}