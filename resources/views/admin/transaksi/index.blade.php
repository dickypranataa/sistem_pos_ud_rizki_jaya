@extends('layouts.admin')

@section('content')
<h2 class="text-2xl font-bold mb-4 text-gray-800">Riwayat Transaksi</h2>

<!-- Search -->
<div class="mb-4">
    <form action="{{ route('admin.transaksi.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
        
        <input type="text" 
               name="search" 
               id="search" 
               value="{{ request('search') }}"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
               placeholder="Cari detail transaksi...">
               
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition">
            Cari
        </button>
        
        @if(request('search'))
            <a href="{{ route('admin.transaksi.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg text-center transition">
                Reset
            </a>
        @endif
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
                <td class="px-6 py-4">{{ $transaksis->tipe_harga }}</td>
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
