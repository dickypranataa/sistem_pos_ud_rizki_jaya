@extends('layouts.admin')

@section('content')

{{-- Header --}}
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Detail Transaksi</h2>
        <p class="text-sm text-gray-500 mt-1">Rincian lengkap transaksi penjualan.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.transaksi.index') }}"
            class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-xl text-sm font-semibold transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
        <a href="{{ route('admin.transaksi.cetak', $transaksi->id) }}" target="_blank"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-xl text-sm font-semibold transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak Struk
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    {{-- Info Header --}}
    <div class="p-6 sm:p-8 border-b border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Kode Transaksi</p>
                <span class="font-mono text-lg font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-xl inline-block">
                    {{ $transaksi->kode_transaksi }}
                </span>
                <p class="text-sm text-gray-500 mt-3">
                    <span class="font-medium text-gray-700">Waktu:</span>
                    {{ \Carbon\Carbon::parse($transaksi->waktu_transaksi)->format('d M Y, H:i') }}
                </p>
            </div>
            <div class="md:text-right">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Informasi Layanan</p>
                <div class="space-y-1.5 text-sm">
                    <p class="text-gray-600">Kasir:
                        <span class="font-semibold text-blue-600">{{ $transaksi->user->name }}</span>
                    </p>
                    <p class="text-gray-600">Pembayaran:
                        <span class="font-semibold text-gray-900">{{ $transaksi->pembayaran->nama_pembayaran }}</span>
                    </p>
                    <p class="text-gray-600">Tipe Harga:
                        <span class="font-semibold text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-lg uppercase ml-1">{{ $transaksi->tipe_harga }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Items Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider">Nama Produk</th>
                    <th class="px-6 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider text-right">Harga Satuan</th>
                    <th class="px-6 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider text-center">Qty</th>
                    <th class="px-6 py-3 font-semibold text-gray-500 text-xs uppercase tracking-wider text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($transaksi->detail as $item)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</td>
                    <td class="px-6 py-4 text-right text-gray-600 text-xs">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="bg-gray-100 text-gray-700 text-xs font-bold px-2.5 py-1 rounded-lg">{{ $item->jumlah }}</span>
                    </td>
                    <td class="px-6 py-4 text-right font-semibold text-gray-900 text-xs">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Summary --}}
    <div class="p-6 sm:p-8 border-t border-gray-100 bg-gray-50/30">
        <div class="flex justify-end">
            <div class="w-full md:w-72 space-y-3">
                <div class="flex justify-between items-center text-sm text-gray-600">
                    <span>Total Belanja</span>
                    <span class="font-bold text-gray-900 text-base">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-sm text-gray-600">
                    <span>Uang Bayar</span>
                    <span class="font-medium text-gray-800">Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                    <span class="text-sm font-semibold text-gray-700">Kembalian</span>
                    <span class="font-bold text-emerald-600 text-lg">Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection