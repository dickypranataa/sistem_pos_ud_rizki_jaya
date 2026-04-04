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

    public function exportPDF(Request $request){
        // 1. Ambil filter dari request (jika ada)
        $filterBulan = $request->input('filter_bulan');
        $filterTipe = $request->input('filter_tipe');

        // 2. Query data sesuai filter
        $riwayat = RiwayatStok::with(['produk', 'user'])
            ->when($filterBulan, function ($query) use ($filterBulan) {
                $waktu = explode('-', $filterBulan);
                if (count($waktu) == 2) {
                    $query->whereYear('created_at', $waktu[0])
                          ->whereMonth('created_at', $waktu[1]);
                }
            })
            ->when($filterTipe, function ($query) use ($filterTipe) {
                $query->where('tipe', $filterTipe);
            })
            ->orderBy('created_at', 'desc')
            ->get(); // Gunakan get(), bukan paginate(), agar semua baris ikut tercetak

        // 3. Nama file dinamis
        $namaFile = 'Laporan_Stok';
        if ($filterBulan) {
            $namaFile .= '_' . $filterBulan;
        }

        // 4. Proses render PDF
        $pdf = Pdf::loadView('admin.riwayat.pdf', compact('riwayat', 'filterBulan', 'filterTipe'))
                  ->setPaper('a4', 'portrait');

        // 5. Unduh (download) atau Tampilkan (stream)
        // Gunakan ->download() untuk langsung unduh, atau ->stream() untuk melihat di browser dulu
        return $pdf->download($namaFile . '.pdf');
    }
}