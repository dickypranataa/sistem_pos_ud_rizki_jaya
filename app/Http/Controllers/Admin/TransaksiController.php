<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Exports\TransaksiExport;
use Maatwebsite\Excel\Facades\Excel;


class TransaksiController extends Controller
{
    //
    public function index(Request $request)
    {
        // 1. Tambahkan Validasi "ATAU" (Saling Eksklusif)
        $request->validate([
            'filter_bulan' => 'nullable|prohibits:filter_tanggal',
            'filter_tanggal' => 'nullable|date|prohibits:filter_bulan',
        ], [
            'filter_bulan.prohibits' => 'Pilih salah satu: Filter berdasarkan Bulan ATAU Tanggal, tidak bisa keduanya.',
            'filter_tanggal.prohibits' => 'Pilih salah satu: Filter berdasarkan Tanggal ATAU Bulan, tidak bisa keduanya.'
        ]);

        $filterBulan = $request->input('filter_bulan');
        $filterTanggal = $request->input('filter_tanggal');

        $transaksi = Transaksi::with(['user', 'pembayaran', 'piutang.pelanggan'])
            ->when($filterBulan, function ($query) use ($filterBulan) {
                $waktu = explode('-', $filterBulan);
                if (count($waktu) == 2) {
                    $query->whereYear('waktu_transaksi', $waktu[0])
                        ->whereMonth('waktu_transaksi', $waktu[1]);
                }
            })
            ->when($filterTanggal, function ($query) use ($filterTanggal) {
                $query->whereDate('waktu_transaksi', $filterTanggal);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.transaksi.index', compact('transaksi'));
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['detail.produk', 'user', 'pembayaran', 'piutang.pelanggan'])->findOrFail($id);


        return view('admin.transaksi.show', compact('transaksi'));
    }

    public function cetakStruk($id)
    {
        $transaksi = Transaksi::with(['detail.produk', 'user', 'pembayaran', 'piutang.pelanggan'])->findOrFail($id);

        return view('admin.transaksi.cetak', compact('transaksi'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'filter_bulan' => 'nullable|prohibits:filter_tanggal',
            'filter_tanggal' => 'nullable|date|prohibits:filter_bulan',
        ]);

        $filterBulan = $request->input('filter_bulan');
        $filterTanggal = $request->input('filter_tanggal');

        $namaFile = 'Laporan_Transaksi';
        if ($filterTanggal) {
            $namaFile .= '_' . $filterTanggal;
        } elseif ($filterBulan) {
            $namaFile .= '_Bulan_' . $filterBulan;
        } else {
            $namaFile .= '_Semua';
        }

        return Excel::download(new TransaksiExport($filterBulan, $filterTanggal), $namaFile . '.xlsx');
    }

}
