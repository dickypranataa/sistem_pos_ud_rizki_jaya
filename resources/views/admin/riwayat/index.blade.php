@extends('layouts.admin')

@section('content')
<div>

    <div class="mb-6">
        <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Riwayat Pergerakan Stok</h2>
        <p class="text-sm text-gray-400 mt-1 font-medium">Pantau semua barang masuk, keluar, dan koreksi stok di sini.</p>
    </div>

    {{-- Filter Card --}}
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm mb-5">
        <form method="GET" action="{{ route('admin.riwayat.index') }}"
            class="flex flex-col md:flex-row gap-4 items-start flex-wrap">

            <div class="w-full md:flex-1">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Cari Produk</label>
                <div class="relative">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama produk..."
                        class="w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white placeholder-gray-400 transition-all duration-200">
                </div>
            </div>

            <div class="w-full md:w-auto">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white transition-all duration-200">
                @error('tanggal_awal') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
            </div>

            <div class="w-full md:w-auto">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white transition-all duration-200">
                @error('tanggal_akhir') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
            </div>

            <div class="w-full md:w-auto flex flex-wrap gap-2.5 md:mt-6">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                    Filter
                </button>
                <a href="{{ route('admin.riwayat.index') }}"
                    class="inline-flex items-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 active:scale-95 text-gray-700 text-sm font-semibold rounded-xl transition-all duration-200">
                    Reset
                </a>
                <a href="{{ route('admin.riwayat.export_pdf', request()->query()) }}"
                    class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white py-2.5 px-4 rounded-xl text-sm font-semibold shadow-sm transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Export PDF
                </a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Perubahan</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Stok Akhir</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Keterangan / Aktor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($riwayats as $riwayat)
                        <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 font-medium">
                                {{ \Carbon\Carbon::parse($riwayat->created_at)->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $riwayat->produk->nama_produk ?? 'Produk Dihapus' }}</div>
                                <div class="text-xs text-gray-400 mt-0.5 font-mono">{{ $riwayat->produk->sku ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($riwayat->tipe === 'sale')
                                    <span class="px-2.5 py-1 inline-flex text-[11px] leading-4 font-bold rounded-lg bg-red-50 text-red-600 border border-red-100">Penjualan</span>
                                @elseif($riwayat->tipe === 'restock')
                                    <span class="px-2.5 py-1 inline-flex text-[11px] leading-4 font-bold rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100">Stok Masuk</span>
                                @else
                                    <span class="px-2.5 py-1 inline-flex text-[11px] leading-4 font-bold rounded-lg bg-amber-50 text-amber-600 border border-amber-100">Koreksi</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center font-black text-base {{ $riwayat->jumlah < 0 ? 'text-red-500' : 'text-emerald-500' }}">
                                {{ $riwayat->jumlah > 0 ? '+' : '' }}{{ $riwayat->jumlah }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="bg-gray-100 text-gray-700 font-bold text-sm px-2.5 py-1 rounded-lg">{{ $riwayat->stok_akhir }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-gray-900 font-medium text-sm">{{ $riwayat->keterangan ?? '-' }}</div>
                                <div class="text-[11px] text-gray-400 mt-0.5">Oleh: {{ $riwayat->user->name ?? 'Sistem' }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-14 text-center text-gray-400">
                                <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                    <svg class="h-7 w-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-400">Belum ada data riwayat pergerakan stok.</p>
                                <p class="text-xs text-gray-300 mt-1">Coba ubah filter pencarian Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($riwayats->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $riwayats->links() }}
            </div>
        @endif
    </div>

</div>
@endsection