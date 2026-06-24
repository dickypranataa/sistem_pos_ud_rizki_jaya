<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Cicilan - {{ $cicilan->piutang->transaksi->kode_transaksi }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 11px; color: #000; margin: 0; padding: 5px; width: 58mm; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .header { margin-bottom: 10px; border-bottom: 1px dashed #000; padding-bottom: 8px; }
        .header h1 { margin: 0; font-size: 14px; }
        .header p { margin: 2px 0; }
        .section { border-top: 1px dashed #000; padding-top: 6px; margin-top: 6px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 3px 0; vertical-align: top; }
        .footer { text-align: center; margin-top: 15px; border-top: 1px dashed #000; padding-top: 10px; font-size: 11px; }
        @media print { .no-print { display: none !important; } body { width: 100%; margin: 0; padding: 0; } @page { margin: 0; } }
        .btn-print { display: block; width: 100%; padding: 10px; background-color: #10b981; color: white; text-align: center; text-decoration: none; font-family: Arial, sans-serif; margin-bottom: 15px; border-radius: 5px; }
    </style>
</head>
<body>
    <a href="#" class="btn-print no-print" onclick="window.print(); return false;">🖨️ Cetak Struk Cicilan</a>

    <div class="header text-center">
        <h1>UD RIZKI JAYA</h1>
        <p>Jl. Raya Talang No.16, Pulo, Kajen</p>
        <p>Kec. Talang, Kabupaten Tegal, Jawa Tengah 52193</p>
    </div>

    <p class="text-center font-bold" style="margin: 5px 0; font-size: 13px;">[ BUKTI PEMBAYARAN CICILAN ]</p>

    <div>
        <table>
            <tr><td>No. Nota</td><td class="text-right">{{ $cicilan->piutang->transaksi->kode_transaksi }}</td></tr>
            <tr><td>Pelanggan</td><td class="text-right font-bold">{{ $cicilan->piutang->pelanggan->nama_pelanggan }}</td></tr>
            @if($cicilan->piutang->pelanggan->alamat)
            <tr><td>Alamat</td><td class="text-right">{{ $cicilan->piutang->pelanggan->alamat }}</td></tr>
            @endif
            <tr><td>Tgl. Bayar</td><td class="text-right">{{ \Carbon\Carbon::parse($cicilan->tanggal_bayar)->format('d M Y') }}</td></tr>
            <tr><td>Diterima Oleh</td><td class="text-right">{{ $cicilan->user->name }}</td></tr>
        </table>
    </div>

    <div class="section">
        <table>
            <tr>
                <td class="font-bold">Total Belanja Asal</td>
                <td class="text-right">Rp {{ number_format($cicilan->piutang->transaksi->total_harga, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="font-bold" style="font-size:13px;">Jumlah Dibayar</td>
                <td class="text-right font-bold" style="font-size:13px;">Rp {{ number_format($cicilan->jumlah_bayar, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Sisa Tagihan</td>
                <td class="text-right">Rp {{ number_format($cicilan->piutang->sisa_tagihan, 0, ',', '.') }}</td>
            </tr>
            @if($cicilan->piutang->status === 'lunas')
            <tr><td colspan="2" class="text-center font-bold" style="padding-top:5px;">★ LUNAS ★</td></tr>
            @else
            <tr>
                <td>Jatuh Tempo</td>
                <td class="text-right">{{ \Carbon\Carbon::parse($cicilan->piutang->jatuh_tempo)->format('d M Y') }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="footer">
        <p>Simpan struk ini sebagai bukti pembayaran.</p>
        <p>Terima kasih atas kepercayaan Anda.</p>
    </div>

    <script>window.onload = function() { window.print(); }</script>
</body>
</html>
