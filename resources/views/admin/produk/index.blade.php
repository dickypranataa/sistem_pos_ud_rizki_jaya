@extends('layouts.admin')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Produk</h2>
        <p class="text-sm text-gray-500 mt-1">Daftar seluruh produk yang tersedia di toko.</p>
    </div>
    <a href="{{ route('admin.produk.create') }}"
        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-2.5 px-4 rounded-xl font-semibold text-sm shadow-sm transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Produk
    </a>
</div>

{{-- Search --}}
<div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm mb-5">
    <form action="{{ route('admin.produk.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
        <div class="relative flex-1">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" id="search" value="{{ request('search') }}"
                class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Cari SKU atau Nama Produk...">
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm font-semibold transition">Cari</button>
        @if(request('search'))
            <a href="{{ route('admin.produk.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 py-2 px-4 rounded-lg text-sm font-semibold transition text-center">Reset</a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-blue-50 border-b border-blue-100">
                    <th class="px-4 py-4 font-semibold text-blue-700 text-xs uppercase tracking-wider">SKU</th>
                    <th class="px-4 py-4 font-semibold text-blue-700 text-xs uppercase tracking-wider">Gambar</th>
                    <th class="px-4 py-4 font-semibold text-blue-700 text-xs uppercase tracking-wider">Nama Produk</th>
                    <th class="px-4 py-4 font-semibold text-blue-700 text-xs uppercase tracking-wider">Kategori</th>
                    <th class="px-4 py-4 font-semibold text-blue-700 text-xs uppercase tracking-wider">Satuan</th>
                    <th class="px-4 py-4 font-semibold text-blue-700 text-xs uppercase tracking-wider text-center">Stok</th>
                    <th class="px-4 py-4 font-semibold text-blue-700 text-xs uppercase tracking-wider">Harga Modal</th>
                    <th class="px-4 py-4 font-semibold text-blue-700 text-xs uppercase tracking-wider">Harga Grosir</th>
                    <th class="px-4 py-4 font-semibold text-blue-700 text-xs uppercase tracking-wider">Semi Grosir</th>
                    <th class="px-4 py-4 font-semibold text-blue-700 text-xs uppercase tracking-wider">Retail</th>
                    <th class="px-4 py-4 font-semibold text-blue-700 text-xs uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($produks as $produk)
                <tr class="hover:bg-gray-50/60 transition">
                    <td class="px-4 py-3">
                        <span class="font-mono text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-md">{{ $produk->sku }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @if ($produk->gambar)
                            <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}"
                                class="w-11 h-11 object-cover rounded-xl border border-gray-100">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($produk->nama_produk) }}&background=EFF6FF&color=2563EB&size=128"
                                alt="No Image" class="w-11 h-11 object-cover rounded-xl border border-gray-100">
                        @endif
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-900 max-w-[160px]">
                        <span class="line-clamp-2">{{ $produk->nama_produk }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-lg">{{ $produk->kategori->nama_kategori }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $produk->satuan }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="font-bold text-sm {{ $produk->stok < 5 ? 'text-red-600' : 'text-gray-800' }}">
                            {{ $produk->stok }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs whitespace-nowrap">{{ 'Rp ' . number_format($produk->harga_modal, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-gray-600 text-xs whitespace-nowrap">{{ 'Rp ' . number_format($produk->harga_grosir, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-gray-600 text-xs whitespace-nowrap">{{ 'Rp ' . number_format($produk->harga_semi_grosir, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-gray-800 text-xs font-semibold whitespace-nowrap">{{ 'Rp ' . number_format($produk->harga_retail, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.produk.edit', $produk->id) }}"
                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            <form id="delete-form-{{ $produk->id }}" action="{{ route('admin.produk.destroy', $produk->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete({{ $produk->id }}, '{{ $produk->nama_produk }}')"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-semibold rounded-lg transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
        {{ $produks->links() }}
    </div>
</div>

<script>
    function confirmDelete(id, namaProduk) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Produk '" + namaProduk + "' akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection