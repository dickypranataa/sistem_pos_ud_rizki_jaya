@extends('layouts.admin')

@section('content')

<h2 class="text-2xl font-bold mb-4 text-gray-800">Tambah Produk</h2>

<form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-4">
            <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Foto Produk (Opsional)</h3>
            
            <div class="flex flex-col md:flex-row gap-6 items-start">
                <div class="w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center">
                    <img class="img-preview w-full h-full object-cover hidden">
                    <span class="text-gray-400 text-xs text-center img-placeholder">Preview<br>Gambar</span>
                </div>

                <div class="flex-1">
                    <input type="file" name="gambar" id="gambar" accept="image/*" onchange="previewImage()"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-500 mt-2">Format yang didukung: JPG, PNG, JPEG. Ukuran maksimal 2MB.</p>
                    @error('gambar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

    <div class="mb-4">
        <label for="sku" class="block text-gray-700 font-bold mb-2">SKU</label>
        <input type="text" name="sku" id="sku" value="{{ old('sku') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        @error('sku') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div class="mb-4">
        <label for="nama_produk" class="block text-gray-700 font-bold mb-2">Nama Produk</label>
        <input type="text" name="nama_produk" id="nama_produk" value="{{ old('nama_produk') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        @error('nama_produk') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div class="mb-4">
        <label for="kategori_id" class="block text-gray-700 font-bold mb-2">Kategori</label>
        <select name="kategori_id" id="kategori_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <option value="">Pilih Kategori</option>
            @foreach ($kategoris as $kategori)
                <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
            @endforeach
        </select>
        @error('kategori_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div class="mb-4">
        <label for="satuan" class="block text-gray-700 font-bold mb-2">Satuan</label>
        <input type="text" name="satuan" id="satuan" value="{{ old('satuan') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        @error('satuan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div class="mb-4">
        <label for="stok" class="block text-gray-700 font-bold mb-2">Stok</label>
        <input type="number" name="stok" id="stok" value="{{ old('stok') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        @error('stok') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div class="mb-4">
        <label for="harga_modal" class="block text-gray-700 font-bold mb-2">Harga Modal</label>
        <input type="number" step="0.01" name="harga_modal" id="harga_modal" value="{{ old('harga_modal') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        @error('harga_modal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div class="mb-4">
        <label for="harga_grosir" class="block text-gray-700 font-bold mb-2">Harga Grosir</label>
        <input type="number" step="0.01" name="harga_grosir" id="harga_grosir" value="{{ old('harga_grosir') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        @error('harga_grosir') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div class="mb-4">
        <label for="harga_semi_grosir" class="block text-gray-700 font-bold mb-2">Harga Semi Grosir</label>
        <input type="number" step="0.01" name="harga_semi_grosir" id="harga_semi_grosir" value="{{ old('harga_semi_grosir') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        @error('harga_semi_grosir') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    <div class="mb-4">
        <label for="harga_retail" class="block text-gray-700 font-bold mb-2">Harga Retail</label>
        <input type="number" step="0.01" name="harga_retail" id="harga_retail" value="{{ old('harga_retail') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        @error('harga_retail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>
    
    <div class="flex justify-end gap-2 mb-10">
        <a href="{{ route('admin.produk.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded">Batal</a>
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Simpan</button>
    </div>
</form>

<script>
    // 1. Inisialisasi Tom Select untuk pencarian kategori
    document.addEventListener("DOMContentLoaded", function() {
        new TomSelect("#kategori_id", {
            create: false, // Matikan fitur tambah kategori dari sini
            sortField: {
                field: "text",
                direction: "asc" // Urutkan nama kategori sesuai abjad
            },
            placeholder: "Ketik untuk mencari kategori..."
        });
    });

    // 2. Fungsi Preview Image (Bawaan asli Mas Dicky)
    function previewImage() {
        const image = document.querySelector('#gambar');
        const imgPreview = document.querySelector('.img-preview');
        const placeholder = document.querySelector('.img-placeholder');

        imgPreview.style.display = 'block';
        imgPreview.classList.remove('hidden');
        if(placeholder) placeholder.style.display = 'none';

        const oFReader = new FileReader();
        oFReader.readAsDataURL(image.files[0]);

        oFReader.onload = function(oFREvent) {
            imgPreview.src = oFREvent.target.result;
        }
    }
</script>
@endsection