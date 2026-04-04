@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                Riwayat Pergerakan Stok
            </h2>
            <p class="text-sm text-gray-500 mt-1">Pantau semua barang masuk, keluar, dan koreksi stok di sini.</p>
        </div>

        <div class="space-y-6">

            <div class="bg-white p-6 shadow-sm sm:rounded-xl border border-gray-100">
                <form method="GET" action="{{ route('admin.riwayat.index') }}"
                    class="flex flex-col md:flex-row gap-4 items-end">

                    <div class="w-full md:w-1/3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cari Produk</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama produk..."
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm">
                    </div>

                    <div class="w-full md:w-1/4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                        <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm">
                    </div>

                    <div class="w-full md:w-1/4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                        <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm">
                    </div>

                    <div class="w-full md:w-auto flex gap-2">
                        <button type="submit"
                            class="inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow-sm">
                            Filter
                        </button>
                        <a href="{{ route('admin.riwayat.index') }}"
                            class="inline-flex items-center px-5 py-2.5 bg-gray-100 border border-transparent rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 transition shadow-sm">
                            Reset
                        </a>
                        <a href="{{ route('admin.riwayat.export_pdf', request()->query()) }}"
                            class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white py-2 px-5 rounded-lg text-center transition font-semibold text-sm shadow-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                            Export PDF
                        </a>
                    </div>

                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/80">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Waktu</th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Produk</th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Jenis</th>
                                <th scope="col"
                                    class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Perubahan</th>
                                <th scope="col"
                                    class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Stok Akhir</th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Keterangan / Aktor</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($riwayats as $riwayat)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($riwayat->created_at)->translatedFormat('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">
                                            {{ $riwayat->produk->nama_produk ?? 'Produk Dihapus' }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $riwayat->produk->sku ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($riwayat->tipe === 'sale')
                                            <span
                                                class="px-2.5 py-1 inline-flex text-[11px] leading-4 font-bold rounded-md bg-red-50 text-red-600 border border-red-100">Penjualan</span>
                                        @elseif($riwayat->tipe === 'restock')
                                            <span
                                                class="px-2.5 py-1 inline-flex text-[11px] leading-4 font-bold rounded-md bg-emerald-50 text-emerald-600 border border-emerald-100">Stok
                                                Masuk</span>
                                        @else
                                            <span
                                                class="px-2.5 py-1 inline-flex text-[11px] leading-4 font-bold rounded-md bg-amber-50 text-amber-600 border border-amber-100">Koreksi</span>
                                        @endif
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-center text-sm font-black {{ $riwayat->jumlah < 0 ? 'text-red-500' : 'text-emerald-500' }}">
                                        {{ $riwayat->jumlah > 0 ? '+' : '' }}{{ $riwayat->jumlah }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-700">
                                        {{ $riwayat->stok_akhir }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="text-gray-900 font-medium">{{ $riwayat->keterangan ?? '-' }}</div>
                                        <div class="text-[11px] text-gray-400 mt-0.5">Oleh:
                                            {{ $riwayat->user->name ?? 'Sistem' }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                        <svg class="mx-auto h-12 w-12 mb-3 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p class="text-sm font-medium">Belum ada data riwayat pergerakan stok.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($riwayats->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $riwayats->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection