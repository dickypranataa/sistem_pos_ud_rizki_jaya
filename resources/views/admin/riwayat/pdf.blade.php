<!DOCTYPE html>
<html>
<head>
    <title>Laporan Riwayat Stok</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; padding: 0; font-size: 20px; }
        .header p { margin: 5px 0 0 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-red { color: #dc2626; }
        .text-green { color: #16a34a; }
        .text-orange { color: #d97706; }
    </style>
</head>
<body>

    <div class="header">
        <h2>UD RIZKI JAYA</h2>
        <p>Laporan Riwayat Pergerakan Stok Barang</p>
        @if($filterBulan)
            <p>Periode: {{ \Carbon\Carbon::parse($filterBulan)->translatedFormat('F Y') }}</p>
        @endif
        <p style="font-size: 10px; text-align: right;">Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">Waktu</th>
                <th width="30%">Nama Produk</th>
                <th width="15%" class="text-center">Jenis</th>
                <th width="10%" class="text-center">Qty (+/-)</th>
                <th width="10%" class="text-center">Sisa</th>
                <th width="20%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</td>
                    
                    <td class="text-center">
                        @if($item->tipe == 'sale') <span class="text-red">Penjualan</span>
                        @elseif($item->tipe == 'restock') <span class="text-green">Restock</span>
                        @else <span class="text-orange">Koreksi</span>
                        @endif
                    </td>
                    
                    <td class="text-center font-bold">
                        {{ $item->jumlah > 0 ? '+' : '' }}{{ $item->jumlah }}
                    </td>
                    
                    <td class="text-center">{{ $item->stok_akhir }}</td>
                    <td>{{ $item->keterangan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data riwayat stok pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>