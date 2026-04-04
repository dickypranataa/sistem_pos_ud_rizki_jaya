<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaksiExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    //
    use Exportable;

    protected $filterBulan;
    protected $filterTanggal;

    public function __construct($filterBulan = null, $filterTanggal = null)
    {
        $this->filterBulan = $filterBulan;
        $this->filterTanggal = $filterTanggal;
    }

    public function query()
    {
        return Transaksi::with(['user', 'pembayaran'])
            ->when($this->filterBulan, function ($query) {
                $waktu = explode('-', $this->filterBulan);
                if (count($waktu) == 2) {
                    $query->whereYear('waktu_transaksi', $waktu[0])
                        ->whereMonth('waktu_transaksi', $waktu[1]);
                }
            })
            ->when($this->filterTanggal, function ($query) {
                $query->whereDate('waktu_transaksi', $this->filterTanggal);
            })
            ->orderBy('waktu_transaksi', 'asc');
    }

    // Menentukan judul kolom di baris pertama Excel
    public function headings(): array
    {
        return [
            'Kode Transaksi',
            'Waktu',
            'Kasir',
            'Metode Pembayaran',
            'Total Belanja (Rp)'
        ];
    }

    // Memetakan isi data ke masing-masing kolom
    public function map($transaksi): array
    {
        return [
            $transaksi->kode_transaksi,
            $transaksi->waktu_transaksi,
            $transaksi->user->name ?? 'Sistem',
            $transaksi->pembayaran->nama_pembayaran ?? '-',
            $transaksi->total_harga
        ];
    }

}
