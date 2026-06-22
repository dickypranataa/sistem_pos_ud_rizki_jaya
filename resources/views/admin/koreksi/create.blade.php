@extends('layouts.admin')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Koreksi Stok Barang</h2>
    <p class="text-sm text-gray-500 mt-1">Catat pergerakan stok masuk, keluar, atau koreksi manual.</p>
</div>

<div class="max-w-3xl">

    @if (session('error'))
        <div class="flex items-center gap-3 bg-red-50 text-red-700 border border-red-100 px-4 py-3 rounded-xl mb-5 text-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
        <form action="{{ route('admin.koreksi.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Produk</label>
                <select name="produk_id" id="pilih-produk" class="w-full rounded-xl border-gray-200" placeholder="Ketik nama produk untuk mencari...">
                    <option value="">-- Ketik / Pilih Produk --</option>
                    @foreach($produks as $produk)
                        <option value="{{ $produk->id }}" {{ old('produk_id') == $produk->id ? 'selected' : '' }}>
                            {{ $produk->nama_produk }} (Sisa Stok: {{ $produk->stok }})
                        </option>
                    @endforeach
                </select>
                @error('produk_id') <span class="text-red-500 text-xs mt-1.5 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Pergerakan Stok</label>
                    <select name="jenis_koreksi" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">-- Pilih Jenis --</option>
                        <optgroup label="Barang Masuk (+)">
                            <option value="restock" {{ old('jenis_koreksi') == 'restock' ? 'selected' : '' }}>Stok Masuk / Restock dari Supplier</option>
                            <option value="correction_plus" {{ old('jenis_koreksi') == 'correction_plus' ? 'selected' : '' }}>Koreksi Plus (Barang Ketemu)</option>
                        </optgroup>
                        <optgroup label="Barang Keluar (-)">
                            <option value="sale" {{ old('jenis_koreksi') == 'sale' ? 'selected' : '' }}>Penjualan Manual (Tanpa Kasir)</option>
                            <option value="correction_minus" {{ old('jenis_koreksi') == 'correction_minus' ? 'selected' : '' }}>Koreksi Minus (Rusak/Hilang)</option>
                        </optgroup>
                    </select>
                    @error('jenis_koreksi') <span class="text-red-500 text-xs mt-1.5 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Barang</label>
                    <input type="number" name="jumlah" value="{{ old('jumlah') }}" min="1"
                        placeholder="0"
                        class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('jumlah') <span class="text-red-500 text-xs mt-1.5 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan / Keterangan</label>
                <textarea name="keterangan" rows="3"
                    placeholder="Contoh: Restock dari Supplier PT. ABC tanggal ini..."
                    class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('keterangan') }}</textarea>
                @error('keterangan') <span class="text-red-500 text-xs mt-1.5 block">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('admin.riwayat.index') }}"
                    class="inline-flex items-center justify-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Koreksi Stok
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        new TomSelect("#pilih-produk", {
            create: false,
            sortField: { field: "text", direction: "asc" }
        });
    });
</script>
@endsection