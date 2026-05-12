@extends('layouts.admin')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Kategori</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola semua kategori produk toko.</p>
    </div>
    <button onclick="openModal()"
        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-2.5 px-4 rounded-xl font-semibold text-sm shadow-sm transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Kategori
    </button>
</div>

{{-- Search --}}
<div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm mb-5">
    <form action="{{ route('admin.kategori.index') }}" method="GET" class="flex gap-2">
        <div class="relative flex-1">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" placeholder="Cari kategori..." value="{{ request('search') }}"
                class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm font-semibold transition">Cari</button>
        @if(request('search'))
            <a href="{{ route('admin.kategori.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 py-2 px-4 rounded-lg text-sm font-semibold transition">Reset</a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-blue-50 border-b border-blue-100">
                    <th class="px-6 py-4 font-semibold text-blue-700 text-xs uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 font-semibold text-blue-700 text-xs uppercase tracking-wider">Nama Kategori</th>
                    <th class="px-6 py-4 font-semibold text-blue-700 text-xs uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($kategoris as $kategori)
                <tr class="hover:bg-gray-50/60 transition">
                    <td class="px-6 py-4 text-gray-400 text-sm">{{ $loop->iteration + $kategoris->firstItem() - 1 }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-900">{{ $kategori->nama_kategori }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <button onclick="openEditModal({{ $kategori->id }}, '{{ addslashes($kategori->nama_kategori) }}')"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </button>
                            <form action="{{ route('admin.kategori.destroy', $kategori->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Hapus kategori ini?')"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-semibold rounded-lg transition">
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
        {{ $kategoris->links() }}
    </div>
</div>

{{-- Modal Tambah --}}
<div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center hidden z-50" id="modal">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800">Tambah Kategori</h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.kategori.store') }}" method="POST" class="p-6">
            @csrf
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori</label>
                <input type="text" name="nama_kategori" id="add_nama_kategori" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Contoh: Minuman, Makanan Ringan...">
                @error('nama_kategori') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">Batal</button>
                <button type="submit"
                    class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center hidden z-50" id="editModal">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800">Edit Kategori</h2>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="editForm" method="POST" class="p-6">
            @csrf
            @method('PUT')
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kategori</label>
                <input type="text" name="nama_kategori" id="edit_nama_kategori" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()"
                    class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">Batal</button>
                <button type="submit"
                    class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('modal').classList.remove('hidden');
        document.getElementById('add_nama_kategori').value = '';
    }
    function closeModal() {
        document.getElementById('modal').classList.add('hidden');
    }
    function openEditModal(id, nama) {
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('edit_nama_kategori').value = nama;
        let url = "{{ route('admin.kategori.update', ':id') }}";
        url = url.replace(':id', id);
        document.getElementById('editForm').action = url;
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endsection