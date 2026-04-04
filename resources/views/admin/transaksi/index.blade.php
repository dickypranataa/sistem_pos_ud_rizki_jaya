@extends('layouts.admin')

@section('content')
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Riwayat Transaksi</h2>

    <!-- Search -->

    <div class="mb-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <form action="{{ route('admin.transaksi.index') }}" method="GET"
            class="flex flex-col md:flex-row gap-4 items-center">

            <label class="font-bold text-gray-700 whitespace-nowrap">Filter Transaksi:</label>

            <div class="flex items-center gap-2 w-full md:w-auto">
                <span class="text-sm text-gray-500">Bulan:</span>
                <input type="month" name="filter_bulan" value="{{ request('filter_bulan') }}"
                    class="w-full md:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer text-sm">
            </div>

            <span class="text-xs font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded-md hidden md:block">ATAU</span>

            <div class="flex items-center gap-2 w-full md:w-auto">
                <span class="text-sm text-gray-500">Tanggal:</span>
                <input type="date" name="filter_tanggal" value="{{ request('filter_tanggal') }}"
                    class="w-full md:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer text-sm">
            </div>

            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto mt-4 md:mt-0">

                <button type="submit"
                    class="inline-flex items-center justify-center w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white py-2 px-5 rounded-lg transition font-semibold text-sm shadow-sm gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Tampilkan
                </button>

                @if(request('filter_bulan') || request('filter_tanggal'))
                    <a href="{{ route('admin.transaksi.index') }}"
                        class="inline-flex items-center justify-center w-full sm:w-auto bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 py-2 px-5 rounded-lg transition font-semibold text-sm shadow-sm gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        Reset
                    </a>
                @endif
            </div>

        </form>
    </div>

    <div class="mb-4">
        <a href="{{ route('admin.transaksi.export', request()->query()) }}"
            class="inline-flex items-center justify-center w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white py-2 px-5 rounded-lg transition font-semibold text-sm shadow-sm gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            Export Excel
        </a>
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
                            <a href="{{ route('admin.transaksi.show', $transaksis->id) }}"
                                class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Detail</a>
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