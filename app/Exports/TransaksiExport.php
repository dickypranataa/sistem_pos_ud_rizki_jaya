<?php

namespace App\Exports;

use App\Models\Transaksi;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

// Menggunakan FromCollection dan WithStyles untuk mewarnai baris
class TransaksiExport implements FromCollection, WithHeadings, WithStyles
{
    use Exportable;

    protected $filterBulan;
    protected $filterTanggal;
    protected $rowWarna = []; // Untuk menyimpan nomor baris mana saja yang akan diwarnai (Baris Induk)

    public function __construct($filterBulan = null, $filterTanggal = null)
    {
        $this->filterBulan = $filterBulan;
        $this->filterTanggal = $filterTanggal;
    }

    public function collection()
    {
        // 1. Ambil data TRANSAKSI beserta DETAILNYA
        $transaksis = Transaksi::with(['user', 'pembayaran', 'detail.produk', 'piutang.pelanggan'])
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
            ->orderBy('waktu_transaksi', 'asc')
            ->get();

        $dataExcel = collect([]);
        $currentRow = 2; // Mulai dari baris ke-2 (karena baris 1 adalah Heading)

        // 2. Susun data secara berjenjang (Master-Detail)
        foreach ($transaksis as $trx) {
            
            // --- BARIS INDUK (INFO NOTA) ---
            $dataExcel->push([
                $trx->kode_transaksi,
                Carbon::parse($trx->waktu_transaksi)->translatedFormat('d M Y H:i'),
                $trx->user->name ?? 'Kasir',
                $trx->pembayaran->nama_pembayaran ?? '-',
                ucwords(str_replace('_', ' ', $trx->tipe_harga ?? 'Retail')), // Format semi_grosir jadi Semi Grosir
                
                // Kolom untuk barang (Dikosongkan di baris induk)
                '--- DAFTAR BARANG DI BAWAH ---', 
                '', '', 
                
                // Info Uang di letakkan di baris induk
                $trx->total_harga,
                $trx->bayar,
                $trx->kembalian,

                // Info Pelanggan & Status Piutang
                $trx->piutang ? ($trx->piutang->pelanggan->nama_pelanggan ?? '-') : '-',
                $trx->piutang ? ($trx->piutang->status === 'lunas' ? 'Lunas' : 'Belum Lunas') : '-'
            ]);

            // Simpan nomor baris ini untuk diwarnai tebal nanti
            $this->rowWarna[] = $currentRow;
            $currentRow++;

            // --- BARIS ANAK (DETAIL BARANG) ---
            foreach ($trx->detail as $index => $item) {
                $dataExcel->push([
                    '', // Kosongkan Kode Trx
                    '', // Kosongkan Waktu
                    '', // Kosongkan Kasir
                    '', // Kosongkan Pembayaran
                    '', // Kosongkan Tipe Harga
                    
                    // Isi data barang
                    ($index + 1) . '. ' . ($item->produk->nama_produk ?? 'Dihapus'),
                    $item->harga_satuan,
                    $item->jumlah,
                    
                    // Kosongkan info total nota
                    $item->subtotal,
                    '', // Uang Bayar kosong
                    '', // Kembalian kosong

                    // Kosongkan info piutang
                    '',
                    ''
                ]);
                $currentRow++;
            }
            
            // Tambahkan 1 baris kosong sebagai pemisah antar nota
            $dataExcel->push(['', '', '', '', '', '', '', '', '', '', '', '', '']);
            $currentRow++;
        }

        return $dataExcel;
    }

    public function headings(): array
    {
        return [
            // Header Induk Nota
            'Kode Nota',
            'Waktu',
            'Kasir',
            'Metode Pembayaran',
            'Tipe Harga',
            
            // Header Detail Barang
            'Nama Produk / Barang',
            'Harga Satuan (Rp)',
            'Qty',
            
            // Header Keuangan
            'Subtotal / Total Nota (Rp)',
            'Uang Bayar (Rp)',
            'Kembalian (Rp)',
            
            // Header Piutang
            'Pelanggan',
            'Status Piutang'
        ];
    }

    // Fungsi sakti untuk mewarnai Excel (Membuatnya terlihat seperti struk bertingkat)
    public function styles(Worksheet $sheet)
    {
        // 1. Warnai baris pertama (Judul Kolom) - diubah ke M1 karena ada 13 kolom (A-M)
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FF1F2937']], // Warna abu-abu gelap
        ]);

        // 2. Warnai semua Baris Induk (Kode Nota) agar tebal dan menonjol
        foreach ($this->rowWarna as $rowNumber) {
            $sheet->getStyle('A' . $rowNumber . ':M' . $rowNumber)->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFF3F4F6']], // Warna abu-abu terang
            ]);
        }

        return [];
    }
}