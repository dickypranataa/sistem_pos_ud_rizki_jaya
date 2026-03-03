@extends('layouts.admin')

@section('content')
<h2 class="text-2xl font-bold mb-4 text-gray-800">Detail Transaksi</h2>
    
<div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 border-b pb-6">
        <div>
            <p class="text-sm text-gray-500 mb-1">Kode Transaksi</p>
            <h3 class="text-xl font-bold text-gray-800">{{ $transaksi->kode_transaksi }}</h3>
            <p class="text-sm text-gray-500 mt-2">Waktu: <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($transaksi->waktu_transaksi)->format('d M Y, H:i') }}</span></p>
        </div>
        <div class="md:text-right">
            <p class="text-sm text-gray-500 mb-1">Informasi Layanan</p>
            <p class="text-sm text-gray-800 font-medium">Kasir: <span class="text-blue-600">{{ $transaksi->user->name }}</span></p>
            <p class="text-sm text-gray-800 font-medium mt-1">Pembayaran: {{ $transaksi->pembayaran->nama_pembayaran }}</p>
            <p class="text-sm text-gray-800 font-medium mt-1">Tipe Harga: <span class="uppercase bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">{{ $transaksi->tipe_harga }}</span></p>
        </div>
    </div>

    <div class="relative overflow-x-auto mb-8 border border-gray-200 rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 uppercase text-xs">
                <tr>
                    <th scope="col" class="px-6 py-3 font-medium">No</th>
                    <th scope="col" class="px-6 py-3 font-medium">Nama Produk</th>
                    <th scope="col" class="px-6 py-3 font-medium text-right">Harga Satuan</th>
                    <th scope="col" class="px-6 py-3 font-medium text-center">Jumlah</th>
                    <th scope="col" class="px-6 py-3 font-medium text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi->detail as $item)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</td>
                    <td class="px-6 py-4 text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center">{{ $item->jumlah }}</td>
                    <td class="px-6 py-4 text-right font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex justify-end">
        <div class="w-full md:w-1/3 space-y-3">
            <div class="flex justify-between text-gray-600">
                <span>Total Belanja</span>
                <span class="font-bold text-gray-800 text-lg">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span>Uang Bayar</span>
                <span class="font-medium text-gray-800">Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</span>
            </div>
            <div class="border-t border-gray-200 pt-2 flex justify-between text-gray-600">
                <span>Kembalian</span>
                <span class="font-bold text-green-600">Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
