@extends('layouts.admin')

@section('content')

{{-- Page Header --}}
<div class="mb-7 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Data Produk</h2>
        <p class="text-sm text-gray-400 mt-1 font-medium">Daftar seluruh produk yang tersedia di toko.</p>
    </div>
    <a href="{{ route('admin.produk.create') }}"
        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white py-2.5 px-5 rounded-2xl font-semibold text-sm shadow-md shadow-blue-200 transition-all duration-200 hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Produk
    </a>
</div>

{{-- Search & Filter --}}
<div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm mb-5">
    <form action="{{ route('admin.produk.index') }}" method="GET"
        class="flex flex-col sm:flex-row gap-2.5 items-stretch sm:items-center">

        {{-- Dropdown Kategori --}}
        <div class="relative sm:w-52 flex-shrink-0">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <select name="kategori_id" id="filter-kategori"
                class="w-full pl-10 pr-8 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 text-gray-700 font-medium transition-all duration-200 appearance-none cursor-pointer">
                <option value="">— Semua Kategori —</option>
                @foreach($kategoris as $kat)
                    <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                        {{ $kat->nama_kategori }}
                    </option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>

        {{-- Input Pencarian --}}
        <div class="relative flex-1">
            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" id="search" value="{{ request('search') }}"
                class="w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 placeholder-gray-400 transition-all duration-200"
                placeholder="Cari SKU atau Nama Produk...">
        </div>

        {{-- Tombol Cari --}}
        <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 active:scale-95 text-white py-2.5 px-5 rounded-xl text-sm font-semibold transition-all duration-200 flex-shrink-0">
            Cari
        </button>

        {{-- Tombol Reset (muncul jika ada filter aktif) --}}
        @if(request('search') || request('kategori_id'))
            <a href="{{ route('admin.produk.index') }}"
                class="bg-gray-100 hover:bg-gray-200 active:scale-95 text-gray-600 py-2.5 px-4 rounded-xl text-sm font-semibold transition-all duration-200 text-center flex-shrink-0">
                Reset
            </a>
        @endif
    </form>

    {{-- Info filter aktif --}}
    @if(request('search') || request('kategori_id'))
    <div class="mt-3 pt-3 border-t border-gray-100 flex flex-wrap gap-2 items-center">
        <span class="text-xs text-gray-400 font-medium">Filter aktif:</span>
        @if(request('kategori_id'))
            @php $namaKat = $kategoris->firstWhere('id', request('kategori_id'))?->nama_kategori; @endphp
            @if($namaKat)
            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-100 rounded-lg text-xs font-semibold">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                Kategori: {{ $namaKat }}
            </span>
            @endif
        @endif
        @if(request('search'))
        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 text-gray-600 border border-gray-200 rounded-lg text-xs font-semibold">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Kata kunci: "{{ request('search') }}"
        </span>
        @endif
        <span class="text-xs text-gray-400 font-medium">— {{ $produks->total() }} produk ditemukan</span>
    </div>
    @endif
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-4 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">SKU</th>
                    <th class="px-4 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">Gambar</th>
                    <th class="px-4 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">Nama Produk</th>
                    <th class="px-4 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">Kategori</th>
                    <th class="px-4 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">Satuan</th>
                    <th class="px-4 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider text-center">Stok</th>
                    <th class="px-4 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">Harga Modal</th>
                    <th class="px-4 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">Harga Grosir</th>
                    <th class="px-4 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">Semi Grosir</th>
                    <th class="px-4 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">Retail</th>
                    <th class="px-4 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($produks as $produk)
                <tr class="hover:bg-slate-50/70 transition-colors duration-150">
                    <td class="px-4 py-3">
                        <span class="font-mono text-xs bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg font-semibold">{{ $produk->sku }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @if ($produk->gambar)
                            <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}"
                                class="w-11 h-11 object-cover rounded-xl border border-gray-100 shadow-sm">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($produk->nama_produk) }}&background=EFF6FF&color=2563EB&size=128"
                                alt="No Image" class="w-11 h-11 object-cover rounded-xl border border-gray-100 shadow-sm">
                        @endif
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-900 max-w-[160px]">
                        <span class="line-clamp-2 text-sm">{{ $produk->nama_produk }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-lg border border-blue-100">{{ $produk->kategori->nama_kategori }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs font-medium">{{ $produk->satuan }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($produk->stok < 5)
                            <span class="inline-flex items-center justify-center min-w-[2rem] px-2 py-1 rounded-lg bg-red-50 border border-red-100 text-red-600 font-bold text-sm">{{ $produk->stok }}</span>
                        @else
                            <span class="font-bold text-sm text-gray-800">{{ $produk->stok }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">{{ 'Rp ' . number_format($produk->harga_modal, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">{{ 'Rp ' . number_format($produk->harga_grosir, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">{{ 'Rp ' . number_format($produk->harga_semi_grosir, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-gray-900 text-xs font-bold whitespace-nowrap">{{ 'Rp ' . number_format($produk->harga_retail, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.produk.edit', $produk->id) }}"
                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 active:scale-95 text-blue-700 text-xs font-semibold rounded-lg transition-all duration-150">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            <form id="delete-form-{{ $produk->id }}" action="{{ route('admin.produk.destroy', $produk->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete({{ $produk->id }}, '{{ $produk->nama_produk }}')"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 hover:bg-red-100 active:scale-95 text-red-700 text-xs font-semibold rounded-lg transition-all duration-150">
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