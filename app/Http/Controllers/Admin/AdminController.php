<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //
    public function index()
{
    // Mengambil data 7 hari terakhir
    $tujuhHariLalu = Carbon::today()->subDays(6);

    $pendapatanHarian = Transaksi::select(
            DB::raw('DATE(waktu_transaksi) as tanggal'),
            DB::raw('SUM(total_harga) as total')
        )
        ->whereDate('waktu_transaksi', '>=', $tujuhHariLalu)
        ->groupBy('tanggal')
        ->orderBy('tanggal', 'asc')
        ->get();

    // Memisahkan data untuk sumbu X (Tanggal) dan sumbu Y (Total Uang)
    $tanggalChart = [];
    $totalChart = [];

    foreach ($pendapatanHarian as $data) {
        // Format tanggal jadi lebih mudah dibaca, misal: "21 Mar"
        $tanggalChart[] = Carbon::parse($data->tanggal)->translatedFormat('d M'); 
        $totalChart[] = (int) $data->total;
    }

    return view('admin.dashboard', compact('tanggalChart', 'totalChart'));
}
}
