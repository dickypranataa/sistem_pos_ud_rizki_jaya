@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Manajemen Piutang</h1>
            <p class="text-sm text-gray-500 mt-0.5">Pantau seluruh nota hutang pelanggan</p>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-medium">
        ✓ {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-medium">
        ✕ {{ session('error') }}
    </div>
    @endif

    {{-- Filter & Search --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
        <form method="GET" action="{{ route('admin.piutang.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Cari nama pelanggan / kode transaksi..."
                    class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <select name="status" class="px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                <option value="belum_lunas" {{ $status == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                <option value="lunas" {{ $status == 'lunas' ? 'selected' : '' }}>Sudah Lunas</option>
                <option value="semua" {{ $status == 'semua' ? 'selected' : '' }}>Semua Status</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                Filter
            </button>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-widest">No. Transaksi</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-widest">Pelanggan</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-widest">Dibuat Oleh</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-widest">Total</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-widest">Sisa Tagihan</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-widest">Jatuh Tempo</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-widest">Status</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($piutangs as $p)
                    @php
                        $isLewatTempo = $p->status === 'belum_lunas' && \Carbon\Carbon::parse($p->jatuh_tempo)->isPast();
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors {{ $isLewatTempo ? 'bg-red-50/50' : '' }}">
                        <td class="px-4 py-3 text-sm font-mono font-semibold text-gray-700">{{ $p->transaksi->kode_transaksi }}</td>
                        <td class="px-4 py-3">
                            <p class="text-sm font-semibold text-gray-800">{{ $p->pelanggan->nama_pelanggan }}</p>
                            @if($p->pelanggan->no_hp)
                            <p class="text-xs text-gray-400">{{ $p->pelanggan->no_hp }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $p->transaksi->user->name }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-700">Rp {{ number_format($p->transaksi->total_harga, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-sm font-bold {{ $p->status === 'lunas' ? 'text-emerald-600' : 'text-red-600' }}">
                            Rp {{ number_format($p->sisa_tagihan, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-sm {{ $isLewatTempo ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                            {{ \Carbon\Carbon::parse($p->jatuh_tempo)->format('d M Y') }}
                            @if($isLewatTempo)<span class="text-[10px] bg-red-100 text-red-700 px-1.5 py-0.5 rounded-md ml-1 font-bold">LEWAT</span>@endif
                        </td>
                        <td class="px-4 py-3">
                            @if($p->status === 'lunas')
                            <span class="px-2.5 py-1 text-[11px] font-bold bg-emerald-100 text-emerald-700 rounded-full">LUNAS</span>
                            @else
                            <span class="px-2.5 py-1 text-[11px] font-bold bg-amber-100 text-amber-700 rounded-full">BELUM LUNAS</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.piutang.show', $p->id) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-gray-400 text-sm">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Tidak ada data piutang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($piutangs->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $piutangs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
