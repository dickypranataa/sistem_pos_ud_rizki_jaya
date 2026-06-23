@extends('layouts.admin')

@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Edit Produk</h2>
    <p class="text-sm text-gray-400 mt-1 font-medium">Perbarui informasi produk yang sudah ada.</p>
</div>

<form action="{{ route('admin.produk.update', $produks->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Foto --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-6">
                <h3 class="text-sm font-bold text-gray-700 mb-4">Foto Produk <span class="text-gray-400 font-normal">(Opsional)</span></h3>

                <div class="w-full aspect-square border-2 border-dashed border-gray-200 hover:border-blue-300 rounded-2xl overflow-hidden bg-gray-50 flex items-center justify-center mb-4 transition-colors duration-200">
                    @if($produks->gambar)
                        <img src="{{ asset('storage/' . $produks->gambar) }}" class="img-preview w-full h-full object-cover block">
                        <div class="img-placeholder hidden"></div>
                    @else
                        <img class="img-preview w-full h-full object-cover hidden">
                        <div class="img-placeholder text-center px-4">
                            <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-400 font-medium">Preview gambar</p>
                        </div>
                    @endif
                </div>

                <input type="file" name="gambar" id="gambar" accept="image/*" onchange="previewImage()"
                    class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer transition-all duration-150">
                <p class="text-xs text-gray-400 mt-2.5 font-medium">Biarkan kosong jika tidak ingin mengubah gambar.</p>
                @error('gambar') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Kolom Kanan: Detail Produk --}}
        <div class="lg:col-span-2 space-y-5">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-bold text-gray-700 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-blue-50 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                    Informasi Dasar
                </h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">SKU</label>
                            <input type="text" name="sku" value="{{ old('sku', $produks->sku) }}"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white transition-all duration-200" required>
                            @error('sku') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Satuan</label>
                            <input type="text" name="satuan" value="{{ old('satuan', $produks->satuan) }}"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white transition-all duration-200" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Nama Produk</label>
                        <input type="text" name="nama_produk" value="{{ old('nama_produk', $produks->nama_produk) }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white transition-all duration-200" required>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Kategori</label>
                            <select name="kategori_id" id="kategori_id"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id', $produks->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Stok</label>
                            <input type="number" name="stok" value="{{ old('stok', $produks->stok) }}" min="0"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white transition-all duration-200" required>
                            @error('stok') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-bold text-gray-700 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-emerald-50 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                    Pengaturan Harga
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach([
                        ['harga_modal', 'Harga Modal', $produks->harga_modal, 'Harga beli dari supplier'],
                        ['harga_grosir', 'Harga Grosir', $produks->harga_grosir, 'Harga untuk pelanggan mitra'],
                        ['harga_semi_grosir', 'Harga Semi Grosir', $produks->harga_semi_grosir, 'Harga untuk pelanggan langganan'],
                        ['harga_retail', 'Harga Retail', $produks->harga_retail, 'Harga eceran normal'],
                    ] as [$field, $label, $value, $hint])
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">{{ $label }}</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-semibold pointer-events-none">Rp</span>
                            <input type="number" step="0.01" min="0" name="{{ $field }}" value="{{ old($field, $value) }}"
                                class="w-full pl-11 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white transition-all duration-200" required>
                        </div>
                        <p class="text-xs text-gray-400 mt-1.5 font-medium">{{ $hint }}</p>
                        @error($field) <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-3 pb-8">
                <a href="{{ route('admin.produk.index') }}"
                    class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 active:scale-95 text-gray-700 text-sm font-semibold rounded-xl transition-all duration-200">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-md shadow-blue-200 hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>

    </div>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        new TomSelect("#kategori_id", {
            create: false,
            sortField: { field: "text", direction: "asc" },
            placeholder: "Ketik untuk mencari kategori..."
        });
    });

    function previewImage() {
        const image = document.querySelector('#gambar');
        const imgPreview = document.querySelector('.img-preview');
        const placeholder = document.querySelector('.img-placeholder');
        imgPreview.style.display = 'block';
        imgPreview.classList.remove('hidden');
        if(placeholder) placeholder.style.display = 'none';
        const oFReader = new FileReader();
        oFReader.readAsDataURL(image.files[0]);
        oFReader.onload = function(oFREvent) { imgPreview.src = oFREvent.target.result; }
    }
</script>
@endsection