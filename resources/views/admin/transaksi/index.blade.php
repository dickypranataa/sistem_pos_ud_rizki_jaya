@extends('layouts.admin')

@section('content')
<h2 class="text-2xl font-bold mb-4 text-gray-800">Riwayat Transaksi</h2>

<!-- Search -->

<div class="mb-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
    <form action="{{ route('admin.transaksi.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center">
        
        <label class="font-bold text-gray-700 whitespace-nowrap">Filter Transaksi:</label>
        
        <div class="flex items-center gap-2 w-full md:w-auto">
            <span class="text-sm text-gray-500">Bulan:</span>
            <input type="month" 
                   name="filter_bulan" 
                   value="{{ request('filter_bulan') }}"
                   class="w-full md:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer text-sm">
        </div>

        <span class="text-xs font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded-md hidden md:block">ATAU</span>

        <div class="flex items-center gap-2 w-full md:w-auto">
            <span class="text-sm text-gray-500">Tanggal:</span>
            <input type="date" 
                   name="filter_tanggal" 
                   value="{{ request('filter_tanggal') }}"
                   class="w-full md:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer text-sm">
        </div>
               
        <div class="flex gap-2 w-full md:w-auto mt-2 md:mt-0">
            <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white py-2 px-5 rounded-lg transition font-semibold text-sm shadow-sm">
                Tampilkan
            </button>
            
            @if(request('filter_bulan') || request('filter_tanggal'))
                <a href="{{ route('admin.transaksi.index') }}" class="w-full md:w-auto bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300 py-2 px-5 rounded-lg text-center transition font-semibold text-sm">
                    Reset
                </a>
            @endif
        </div>
        
    </form>
</div>

<div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
<table class="w-full text-sm text-left rtl:text-right text-body">
    <thead class="bg-neutral-secondary-soft border-b border-default">
        <tr>
            <th scope="col" class="px-6 py-3 font-medium">Kode Transaksi</th>
            <th scope="col" class="px-6 py-3 font-medium">Metode Pembayaran</th>
            <th scope="col" class="px-6 py-3 font-medium">Kasir</th>
            <th scope="col" class="px-6 py-3 font-medium">Waktu Transaksi</th>
            <th scope="col" class="px-6 py-3 font-medium">Tipe Harga</th>
            <th scope="col" class="px-6 py-3 font-medium">Total Harga</th>
            <th scope="col" class="px-6 py-3 font-medium">Bayar</th>
            <th scope="col" class="px-6 py-3 font-medium">Kembalian</th>
            <th scope="col" class="px-6 py-3 font-medium">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transaksi as $transaksis)
            <tr class="odd:bg-neutral-primary even:bg-neutral-secondary-soft border-b border-default">
                <td class="px-6 py-4">{{ $transaksis->kode_transaksi }}</td>
                <td class="px-6 py-4">{{ $transaksis->pembayaran->nama_pembayaran}}</td>
                <td class="px-6 py-4 text-blue-500">{{ $transaksis->user->name}}</td>
                <td class="px-6 py-4">{{ $transaksis->waktu_transaksi}}</td>
                <td class="px-6 py-4">{{ Str::headline($transaksis->tipe_harga) }}</td>
                <td class="px-6 py-4">{{ 'Rp ' . number_format($transaksis->total_harga, 0, ',', '.')  }}</td>
                <td class="px-6 py-4">{{ 'Rp ' . number_format($transaksis->bayar, 0, ',', '.')  }}</td>
                <td class="px-6 py-4">{{ 'Rp ' . number_format($transaksis->kembalian, 0, ',', '.')  }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.transaksi.show', $transaksis->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Detail</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>

{{-- Pagination --}}
    <div class="mt-4">
        {{ $transaksi->links() }}
    </div>
@endsection
