@extends('layouts.kasir')

@section('content')
<div class="space-y-6">

    <div class="flex items-center gap-3">
        <a href="{{ route('kasir.piutang.index') }}" class="p-2 hover:bg-gray-100 rounded-xl transition-colors">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">Detail Piutang</h1>
            <p class="text-sm text-gray-500">{{ $piutang->transaksi->kode_transaksi }} — {{ $piutang->pelanggan->nama_pelanggan }}</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-medium">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-medium">✕ {{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kiri: Info --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-start justify-between mb-4">
                    <h2 class="text-base font-bold text-gray-800">Informasi Piutang</h2>
                    @if($piutang->status === 'lunas')
                    <span class="px-3 py-1 text-xs font-bold bg-emerald-100 text-emerald-700 rounded-full">✓ LUNAS</span>
                    @else
                    <span class="px-3 py-1 text-xs font-bold bg-amber-100 text-amber-700 rounded-full">⚠ BELUM LUNAS</span>
                    @endif
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-400 text-xs font-semibold uppercase tracking-widest mb-1">Pelanggan</p>
                        <p class="font-bold text-gray-800">{{ $piutang->pelanggan->nama_pelanggan }}</p>
                        @if($piutang->pelanggan->no_hp)<p class="text-gray-500 text-xs">{{ $piutang->pelanggan->no_hp }}</p>@endif
                        @if($piutang->pelanggan->alamat)<p class="text-gray-500 text-xs">{{ $piutang->pelanggan->alamat }}</p>@endif
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs font-semibold uppercase tracking-widest mb-1">Tanggal Transaksi</p>
                        <p class="font-semibold text-gray-700">{{ \Carbon\Carbon::parse($piutang->transaksi->waktu_transaksi)->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs font-semibold uppercase tracking-widest mb-1">Total Belanja</p>
                        <p class="font-bold text-gray-800 text-base">Rp {{ number_format($piutang->transaksi->total_harga, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs font-semibold uppercase tracking-widest mb-1">Sisa Tagihan</p>
                        <p class="font-bold text-red-600 text-base">Rp {{ number_format($piutang->sisa_tagihan, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs font-semibold uppercase tracking-widest mb-1">Jatuh Tempo</p>
                        @php $lewat = $piutang->status === 'belum_lunas' && \Carbon\Carbon::parse($piutang->jatuh_tempo)->isPast(); @endphp
                        <p class="font-semibold {{ $lewat ? 'text-red-600' : 'text-gray-700' }}">
                            {{ \Carbon\Carbon::parse($piutang->jatuh_tempo)->format('d M Y') }}
                            @if($lewat)<span class="text-xs bg-red-100 text-red-600 px-1.5 py-0.5 rounded ml-1">LEWAT</span>@endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Riwayat Cicilan --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-700">Riwayat Pembayaran</h2>
                </div>
                @if($piutang->pembayaranPiutangs->isEmpty())
                <p class="px-5 py-4 text-sm text-gray-400">Belum ada cicilan.</p>
                @else
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-[11px] font-bold text-gray-400 uppercase">Tanggal</th>
                            <th class="px-4 py-2 text-left text-[11px] font-bold text-gray-400 uppercase">Diterima Oleh</th>
                            <th class="px-4 py-2 text-right text-[11px] font-bold text-gray-400 uppercase">Jumlah</th>
                            <th class="px-4 py-2 text-center text-[11px] font-bold text-gray-400 uppercase">Struk</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($piutang->pembayaranPiutangs as $c)
                        <tr>
                            <td class="px-4 py-2.5 text-sm text-gray-600">{{ \Carbon\Carbon::parse($c->tanggal_bayar)->format('d M Y') }}</td>
                            <td class="px-4 py-2.5 text-sm text-gray-700 font-medium">{{ $c->user->name }}</td>
                            <td class="px-4 py-2.5 text-sm font-bold text-emerald-600 text-right">Rp {{ number_format($c->jumlah_bayar, 0, ',', '.') }}</td>
                            <td class="px-4 py-2.5 text-center">
                                <a href="{{ route('kasir.piutang.cetak_cicilan', [$piutang->id, $c->id]) }}" target="_blank"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg">Cetak</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>

            {{-- Riwayat Perpanjangan --}}
            @if($piutang->riwayatPerpanjanganTempos->isNotEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-700">Riwayat Perpanjangan Tempo</h2>
                </div>
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-[11px] font-bold text-gray-400 uppercase">Tempo Lama</th>
                            <th class="px-4 py-2 text-left text-[11px] font-bold text-gray-400 uppercase">Tempo Baru</th>
                            <th class="px-4 py-2 text-left text-[11px] font-bold text-gray-400 uppercase">Disetujui Oleh</th>
                            <th class="px-4 py-2 text-left text-[11px] font-bold text-gray-400 uppercase">Alasan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($piutang->riwayatPerpanjanganTempos as $r)
                        <tr>
                            <td class="px-4 py-2.5 text-sm text-gray-500 line-through">{{ \Carbon\Carbon::parse($r->tempo_lama)->format('d M Y') }}</td>
                            <td class="px-4 py-2.5 text-sm font-semibold text-gray-700">{{ \Carbon\Carbon::parse($r->tempo_baru)->format('d M Y') }}</td>
                            <td class="px-4 py-2.5 text-sm text-gray-600">{{ $r->user->name }}</td>
                            <td class="px-4 py-2.5 text-sm text-gray-500">{{ $r->alasan_perpanjangan ?: '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- Kanan: Aksi --}}
        <div class="space-y-4">
            @if($piutang->status === 'belum_lunas')
            {{-- Terima Pembayaran --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-bold text-gray-800 mb-3">Terima Pembayaran</h3>
                <form method="POST" action="{{ route('kasir.piutang.bayar', $piutang->id) }}">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Jumlah Bayar (Rp)</label>
                            <input type="number" name="jumlah_bayar" min="1" max="{{ $piutang->sisa_tagihan }}"
                                placeholder="Maks: {{ number_format($piutang->sisa_tagihan, 0, ',', '.') }}"
                                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-400">
                            @error('jumlah_bayar')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Tanggal Bayar</label>
                            <input type="date" name="tanggal_bayar" value="{{ now()->format('Y-m-d') }}"
                                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-400">
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition-colors">
                            Simpan Pembayaran
                        </button>
                    </div>
                </form>
            </div>

            {{-- Perpanjang Tempo --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-bold text-gray-800 mb-3">Perpanjang Tempo</h3>
                <form method="POST" action="{{ route('kasir.piutang.perpanjang', $piutang->id) }}">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Tempo Baru</label>
                            <input type="date" name="tempo_baru" min="{{ now()->addDay()->format('Y-m-d') }}"
                                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Alasan (opsional)</label>
                            <input type="text" name="alasan_perpanjangan" placeholder="Alasan perpanjangan..."
                                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-colors">
                            Perpanjang Tempo
                        </button>
                    </div>
                </form>
            </div>
            @else
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5 text-center">
                <p class="text-sm font-bold text-emerald-700">✓ Piutang Lunas</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
