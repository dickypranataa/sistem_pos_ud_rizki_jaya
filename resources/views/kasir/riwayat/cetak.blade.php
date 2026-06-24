<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi - {{ $transaksi->kode_transaksi }}</title>
    <style>
        /* CSS Khusus Printer Thermal */
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            color: #000;
            margin: 0;
            padding: 5px;
            width: 58mm; /* Diubah ke 58mm untuk menyesuaikan dengan printer thermal 58mm */
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .header { margin-bottom: 12px; border-bottom: 1px dashed #000; padding-bottom: 8px; }
        .header h1 { margin: 0; font-size: 14px; }
        .header p { margin: 2px 0; }
        .content table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .content th, .content td { padding: 4px 0; vertical-align: top; }
        .content .item-name { text-align: left; display: block; }
        .total-area { border-top: 1px dashed #000; padding-top: 5px; margin-top: 5px; }
        .total-area table { width: 100%; }
        .footer { text-align: center; margin-top: 20px; border-top: 1px dashed #000; padding-top: 10px; }
        .piutang-box { border: 2px dashed #000; padding: 6px; margin: 10px 0; }
        .piutang-box .label { font-weight: bold; font-size: 11px; text-align: center; margin-bottom: 4px; }
        
        /* Menyembunyikan tombol saat struk di-print */
        @media print {
            .no-print { display: none !important; }
            body { width: 100%; margin: 0; padding: 0; }
            @page { margin: 0; }
        }

        /* Tombol print manual (hanya terlihat di layar) */
        .btn-print {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #3b82f6;
            color: white;
            text-align: center;
            text-decoration: none;
            font-family: Arial, sans-serif;
            margin-bottom: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <a href="#" class="btn-print no-print" onclick="window.print(); return false;">🖨️ Cetak Sekarang</a>

    <div class="header text-center">
        <h1>UD RIZKI JAYA</h1>
        <p>Jl. Raya Talang No.16, Pulo, Kajen</p>
        <p>Kec. Talang, Kabupaten Tegal, Jawa Tengah 52193</p>
        <p>Telp: 0812-3456-7890</p>
    </div>

    <div style="margin-bottom: 10px;">
        <p style="margin: 0;">Trx  : {{ $transaksi->kode_transaksi }}</p>
        <p style="margin: 0;">Tgl  : {{ \Carbon\Carbon::parse($transaksi->waktu_transaksi)->format('d-m-Y H:i') }}</p>
        <p style="margin: 0;">Kasir: {{ $transaksi->user->name }}</p>
        <p style="margin: 0;">Pemb : {{ $transaksi->pembayaran->nama_pembayaran }}</p>
    </div>

    <div class="content">
        <table style="border-top: 1px dashed #000; border-bottom: 1px dashed #000;">
            @foreach ($transaksi->detail as $item)
            <tr>
                <td colspan="3" class="item-name font-bold">{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</td>
            </tr>
            <tr>
                <td style="width: 20%;">{{ $item->jumlah }}x</td>
                <td style="width: 40%;">{{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-right" style="width: 40%;">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </table>
    </div>

    @php $piutang = $transaksi->piutang; @endphp

    @if($piutang)
    {{-- ===== STRUK PIUTANG ===== --}}
    <div class="piutang-box">
        <div class="label">
            @if($piutang->status === 'lunas')
                ★ SUDAH LUNAS ★
            @else
                ★ BELUM LUNAS ★
            @endif
        </div>
        <table style="width:100%">
            <tr>
                <td>Pelanggan</td>
                <td class="text-right font-bold">{{ $piutang->pelanggan->nama_pelanggan }}</td>
            </tr>
            @if($piutang->pelanggan->alamat)
            <tr>
                <td>Alamat</td>
                <td class="text-right">{{ $piutang->pelanggan->alamat }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="total-area">
        <table>
            <tr>
                <td class="font-bold">Total Belanja</td>
                <td class="font-bold text-right">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
            </tr>
            @php $dp = $transaksi->total_harga - $piutang->sisa_tagihan; @endphp
            @if($dp > 0)
            <tr>
                <td>DP / Uang Muka</td>
                <td class="text-right">Rp {{ number_format($dp, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr>
                <td class="font-bold">Sisa Tagihan</td>
                <td class="font-bold text-right">Rp {{ number_format($piutang->sisa_tagihan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Jatuh Tempo</td>
                <td class="text-right">{{ \Carbon\Carbon::parse($piutang->jatuh_tempo)->format('d M Y') }}</td>
            </tr>
        </table>
    </div>
    @else
    {{-- ===== STRUK TUNAI ===== --}}
    <div class="total-area">
        <table>
            <tr>
                <td class="font-bold">Total</td>
                <td class="font-bold text-right">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Bayar</td>
                <td class="text-right">Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembali</td>
                <td class="text-right">Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>Terima Kasih Telah Berbelanja</p>
        <p>Barang yang sudah dibeli<br>tidak dapat dikembalikan.</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>