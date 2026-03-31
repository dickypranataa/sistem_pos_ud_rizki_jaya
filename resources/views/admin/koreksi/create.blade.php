@extends('layouts.admin')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Koreksi Stok Barang</h2>
    </div>

    <div class="bg-white p-8 shadow-sm sm:rounded-xl border border-gray-100 max-w-3xl">
        
        @if (session('success'))
            <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 text-red-700 px-4 py-3 rounded-lg mb-6">{{ session('error') }}</div>
        @endif

        <form action="{{ route('admin.koreksi.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Produk</label>
                <select name="produk_id" id="pilih-produk" class="w-full rounded-lg border-gray-300" placeholder="Ketik nama produk untuk mencari...">
                    <option value="">-- Ketik / Pilih Produk --</option>
                    @foreach($produks as $produk)
                        <option value="{{ $produk->id }}" {{ old('produk_id') == $produk->id ? 'selected' : '' }}>
                            {{ $produk->nama_produk }} (Sisa Stok: {{ $produk->stok }})
                        </option>
                    @endforeach
                </select>
                @error('produk_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Pergerakan Stok</label>
                    <select name="jenis_koreksi" class="w-full rounded-lg border-gray-300">
                        <option value="">-- Pilih Jenis --</option>
                        <optgroup label="Barang Masuk (+)">
                            <option value="restock" {{ old('jenis_koreksi') == 'restock' ? 'selected' : '' }}>Stok Masuk / Restock dari Supplier</option>
                            <option value="correction_plus" {{ old('jenis_koreksi') == 'correction_plus' ? 'selected' : '' }}>Koreksi Plus (Barang Nyelip Ketemu)</option>
                        </optgroup>
                        <optgroup label="Barang Keluar (-)">
                            <option value="sale" {{ old('jenis_koreksi') == 'sale' ? 'selected' : '' }}>Penjualan Manual (Tanpa Kasir)</option>
                            <option value="correction_minus" {{ old('jenis_koreksi') == 'correction_minus' ? 'selected' : '' }}>Koreksi Minus (Barang Rusak/Hilang)</option>
                        </optgroup>
                    </select>
                    @error('jenis_koreksi') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Barang</label>
                    <input type="number" name="jumlah" value="{{ old('jumlah') }}" min="1" class="w-full rounded-lg border-gray-300">
                    @error('jumlah') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Alasan Koreksi</label>
                <textarea name="keterangan" rows="3" class="w-full rounded-lg border-gray-300">{{ old('keterangan') }}</textarea>
                @error('keterangan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4 border-t flex justify-end">
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700">
                    Simpan Koreksi Stok
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Mengubah select biasa menjadi select dengan fitur pencarian
        new TomSelect("#pilih-produk", {
            create: false, // Matikan fitur tambah produk baru dari sini
            sortField: {
                field: "text",
                direction: "asc" // Urutkan sesuai abjad A-Z
            }
        });
    });
</script>

@endsection