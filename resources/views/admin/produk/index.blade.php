@extends('layouts.admin')

@section('content')
<h2 class="text-2xl font-bold mb-4 text-gray-800">Produk</h2>

<div class="flex items-center justify-between mb-4">
    <a href="{{ route('admin.produk.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
        Tambah Produk
    </a>
</div>

<!-- Search -->
<div class="mb-4">
    <form action="{{ route('admin.produk.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
        
        <input type="text" 
               name="search" 
               id="search" 
               value="{{ request('search') }}"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
               placeholder="Cari SKU atau Nama Produk...">
               
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition">
            Cari
        </button>
        
        @if(request('search'))
            <a href="{{ route('admin.produk.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg text-center transition">
                Reset
            </a>
        @endif
    </form>
</div>

<div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
<table class="w-full text-sm text-left rtl:text-right text-body">
    <thead class="bg-neutral-secondary-soft border-b border-default">
        <tr>
            <th scope="col" class="px-6 py-3 font-medium">SKU</th>
            <th scope="col" class="px-6 py-3 font-medium">Gambar</th>
            <th scope="col" class="px-6 py-3 font-medium">Nama Produk</th>
            <th scope="col" class="px-6 py-3 font-medium">Kategori</th>
            <th scope="col" class="px-6 py-3 font-medium">Satuan</th>
            <th scope="col" class="px-6 py-3 font-medium">Stok</th>
            <th scope="col" class="px-6 py-3 font-medium">Harga Modal</th>
            <th scope="col" class="px-6 py-3 font-medium">Harga Grosir</th>
            <th scope="col" class="px-6 py-3 font-medium">Harga Semi Grosir</th>
            <th scope="col" class="px-6 py-3 font-medium">Harga Retail</th>
            <th scope="col" class="px-6 py-3 font-medium">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($produks as $produk)
            <tr class="odd:bg-neutral-primary even:bg-neutral-secondary-soft border-b border-default">
                <td class="px-6 py-4">{{ $produk->sku }}</td>
                <td class="px-6 py-4">
                    @if ($produk->gambar)
                        <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}" class="w-12 h-12 object-cover rounded-md shadow-sm">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($produk->nama_produk) }}&background=random&color=fff&size=128" 
                            alt="No Image" 
                            class="w-12 h-12 object-cover rounded-md shadow-sm">
                    @endif
                </td>
                <td class="px-6 py-4">{{ $produk->nama_produk }}</td>
                <td class="px-6 py-4 text-blue-500">{{ $produk->kategori->nama_kategori }}</td>
                <td class="px-6 py-4">{{ $produk->satuan }}</td>
                <td class="px-6 py-4">{{ $produk->stok }}</td>
                <td class="px-6 py-4">{{ 'Rp ' . number_format($produk->harga_modal, 0, ',', '.') }}</td>
                <td class="px-6 py-4">{{ 'Rp ' . number_format($produk->harga_grosir, 0, ',', '.') }}</td>
                <td class="px-6 py-4">{{ 'Rp ' . number_format($produk->harga_semi_grosir, 0, ',', '.') }}</td>
                <td class="px-6 py-4">{{ 'Rp ' . number_format($produk->harga_retail, 0, ',', '.') }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.produk.edit', $produk->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Edit</a>
                    <!--hapus dengan sweetalert-->
                    <form id="delete-form-{{ $produk->id }}" action="{{ route('admin.produk.destroy', $produk->id) }}" method="POST" class="inline">
                            @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDelete({{ $produk->id }}, '{{ $produk->nama_produk }}')" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded transition">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>

{{-- Pagination --}}
    <div class="mt-4">
        {{ $produks->links() }}
    </div>

<script>
    function confirmDelete(id, namaProduk) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Produk '" + namaProduk + "' akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', // Warna merah standard Tailwind
            cancelButtonColor: '#6b7280', // Warna abu-abu
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika user klik "Ya, Hapus!", submit form-nya
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection
