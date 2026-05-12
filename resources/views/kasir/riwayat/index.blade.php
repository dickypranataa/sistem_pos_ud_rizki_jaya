@extends('layouts.kasir')

@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Riwayat Transaksi</h2>
    <p class="text-sm text-gray-500 mt-1">Pantau seluruh riwayat transaksi penjualan.</p>
</div>

{{-- Filter Card --}}
<div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm mb-4">
    <form action="{{ route('admin.transaksi.index') }}" method="GET"
        class="flex flex-col md:flex-row gap-4 items-start md:items-end flex-wrap">

        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Bulan</label>
            <input type="month" name="filter_bulan" value="{{ request('filter_bulan') }}"
                class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
        </div>

        <div class="hidden md:flex items-center pb-1">
            <span class="text-xs font-bold text-gray-300 bg-gray-100 px-2.5 py-1 rounded-lg">ATAU</span>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Tanggal</label>
            <input type="date" name="filter_tanggal" value="{{ request('filter_tanggal') }}"
                class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
        </div>

        <div class="flex gap-2">
            <button type="submit"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-2 px-5 rounded-xl text-sm font-semibold transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Tampilkan
            </button>
            @if(request('filter_bulan') || request('filter_tanggal'))
                <a href="{{ route('admin.transaksi.index') }}"
                    class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-xl text-sm font-semibold transition">
                    Reset
                </a>
            @endif
        </div>
    </form>
    @error('filter_bulan') <span class="text-red-500 text-xs block mt-2">{{ $message }}</span> @enderror
    @error('filter_tanggal') <span class="text-red-500 text-xs block mt-2">{{ $message }}</span> @enderror
</div>

{{-- Export --}}
<div class="mb-4">
    <a href="{{ route('admin.transaksi.export', request()->query()) }}"
        class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white py-2 px-5 rounded-xl text-sm font-semibold shadow-sm transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Export Excel
    </a>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-blue-50 border-b border-blue-100">
                    <th class="px-5 py-4 font-semibold text-blue-700 text-xs uppercase tracking-wider whitespace-nowrap">Kode Transaksi</th>
                    <th class="px-5 py-4 font-semibold text-blue-700 text-xs uppercase tracking-wider">Pembayaran</th>
                    <th class="px-5 py-4 font-semibold text-blue-700 text-xs uppercase tracking-wider">Kasir</th>
                    <th class="px-5 py-4 font-semibold text-blue-700 text-xs uppercase tracking-wider whitespace-nowrap">Waktu</th>
                    <th class="px-5 py-4 font-semibold text-blue-700 text-xs uppercase tracking-wider">Tipe</th>
                    <th class="px-5 py-4 font-semibold text-blue-700 text-xs uppercase tracking-wider text-right">Total</th>
                    <th class="px-5 py-4 font-semibold text-blue-700 text-xs uppercase tracking-wider text-right">Bayar</th>
                    <th class="px-5 py-4 font-semibold text-blue-700 text-xs uppercase tracking-wider text-right">Kembali</th>
                    <th class="px-5 py-4 font-semibold text-blue-700 text-xs uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($transaksi as $transaksis)
                <tr class="hover:bg-gray-50/60 transition">
                    <td class="px-5 py-3">
                        <span class="font-mono text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">{{ $transaksis->kode_transaksi }}</span>
                    </td>
                    <td class="px-5 py-3 text-gray-600 text-xs">{{ $transaksis->pembayaran->nama_pembayaran }}</td>
                    <td class="px-5 py-3">
                        <span class="text-blue-600 font-medium text-xs">{{ $transaksis->user->name }}</span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs whitespace-nowrap">{{ $transaksis->waktu_transaksi }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-lg">{{ Str::headline($transaksis->tipe_harga) }}</span>
                    </td>
                    <td class="px-5 py-3 text-right font-bold text-gray-900 text-xs whitespace-nowrap">{{ 'Rp ' . number_format($transaksis->total_harga, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 text-right text-gray-600 text-xs whitespace-nowrap">{{ 'Rp ' . number_format($transaksis->bayar, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 text-right text-emerald-600 font-semibold text-xs whitespace-nowrap">{{ 'Rp ' . number_format($transaksis->kembalian, 0, ',', '.') }}</td>
                    <td class="px-5 py-3">
                        <a href="{{ route('admin.transaksi.show', $transaksis->id) }}"
                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg transition">
                            Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
        {{ $transaksi->links() }}
    </div>
</div>
@endsection