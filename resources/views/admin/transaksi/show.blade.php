@extends('layouts.admin')

@section('content')

{{-- Header --}}
<div class="mb-7 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Detail Transaksi</h2>
        <p class="text-sm text-gray-400 mt-1 font-medium">Rincian lengkap transaksi penjualan.</p>
    </div>
    <div class="flex gap-2.5">
        <a href="{{ route('admin.transaksi.index') }}"
            class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 active:scale-95 text-gray-700 py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
        <a href="{{ route('admin.transaksi.cetak', $transaksi->id) }}" target="_blank"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white py-2.5 px-5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-md shadow-blue-200 hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak Struk
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    {{-- Info Header --}}
    <div class="p-6 sm:p-8 border-b border-gray-100 bg-gradient-to-br from-blue-50/50 to-white">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Kode Transaksi</p>
                <span class="font-mono text-base font-extrabold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-xl inline-block border border-blue-100">
                    {{ $transaksi->kode_transaksi }}
                </span>
                <p class="text-xs text-gray-500 mt-3 font-medium">
                    <span class="font-bold text-gray-700">Waktu:</span>
                    {{ \Carbon\Carbon::parse($transaksi->waktu_transaksi)->format('d M Y, H:i') }}
                </p>
            </div>
            <div class="md:text-right">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Informasi Layanan</p>
                <div class="space-y-2 text-sm">
                    <p class="text-gray-500 font-medium">Kasir:
                        <span class="font-bold text-blue-600">{{ $transaksi->user->name }}</span>
                    </p>
                    <p class="text-gray-500 font-medium">Pembayaran:
                        <span class="font-bold text-gray-900">{{ $transaksi->pembayaran->nama_pembayaran }}</span>
                    </p>
                    <p class="text-gray-500 font-medium">Tipe Harga:
                        <span class="font-bold text-xs bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg uppercase ml-1 border border-blue-100">{{ $transaksi->tipe_harga }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if($transaksi->piutang)
    <div class="px-6 py-4 border-b border-gray-100 bg-amber-50/50 flex flex-col sm:flex-row justify-between gap-4">
        <div>
            <h4 class="text-xs font-bold text-amber-700 uppercase tracking-widest mb-1.5">Informasi Piutang Pelanggan</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-1 text-sm">
                <p class="text-gray-600 font-medium">Nama Pelanggan: <span class="font-bold text-gray-900">{{ $transaksi->piutang->pelanggan->nama_pelanggan }}</span></p>
                <p class="text-gray-600 font-medium">No. HP: <span class="font-bold text-gray-900">{{ $transaksi->piutang->pelanggan->no_hp ?? '-' }}</span></p>
                <p class="text-gray-600 font-medium">Jatuh Tempo: <span class="font-bold {{ \Carbon\Carbon::parse($transaksi->piutang->jatuh_tempo)->isPast() && $transaksi->piutang->status === 'belum_lunas' ? 'text-red-600' : 'text-gray-900' }}">{{ \Carbon\Carbon::parse($transaksi->piutang->jatuh_tempo)->format('d M Y') }}</span></p>
                <p class="text-gray-600 font-medium">Sisa Tagihan: <span class="font-bold text-red-600">Rp {{ number_format($transaksi->piutang->sisa_tagihan, 0, ',', '.') }}</span></p>
            </div>
        </div>
        <div class="flex items-center">
            @if($transaksi->piutang->status === 'lunas')
                <span class="px-3 py-1.5 text-xs font-bold bg-emerald-100 text-emerald-700 rounded-full border border-emerald-200">SUDAH LUNAS</span>
            @else
                <span class="px-3 py-1.5 text-xs font-bold bg-amber-100 text-amber-700 rounded-full border border-amber-200">BELUM LUNAS</span>
            @endif
        </div>
    </div>
    @endif

    {{-- Items Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">No</th>
                    <th class="px-6 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Nama Produk</th>
                    <th class="px-6 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider text-right">Harga Satuan</th>
                    <th class="px-6 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider text-center">Qty</th>
                    <th class="px-6 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($transaksi->detail as $item)
                <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                    <td class="px-6 py-4 text-gray-400 text-xs font-medium">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</td>
                    <td class="px-6 py-4 text-right text-gray-500 text-xs font-medium">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-lg border border-blue-100">{{ $item->jumlah }}</span>
                    </td>
                    <td class="px-6 py-4 text-right font-bold text-gray-900 text-xs">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Summary --}}
    <div class="p-6 sm:p-8 border-t border-gray-100 bg-slate-50/50">
        <div class="flex justify-end">
            <div class="w-full md:w-72 space-y-3">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500 font-medium">Total Belanja</span>
                    <span class="font-extrabold text-gray-900 text-base">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500 font-medium">Uang Bayar</span>
                    <span class="font-semibold text-gray-700">Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center pt-3.5 border-t border-gray-200 mt-1">
                    <span class="text-sm font-bold text-gray-700">Kembalian</span>
                    <span class="font-extrabold text-emerald-600 text-xl">Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection