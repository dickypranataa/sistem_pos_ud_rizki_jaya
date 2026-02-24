@extends('layouts.admin')

@section('content')
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Kategori</h2>

    <div class="flex items-center justify-between mb-4">
        <button onclick="openModal()" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded transition">
            Tambah Kategori
        </button>
    </div>

    <div class="flex justify-end mb-4">
        <form action="{{ route('admin.kategori.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" placeholder="Cari kategori..." value="{{ request('search') }}" class="border border-gray-300 rounded px-3 py-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Cari</button>
        </form>
    </div>

    <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
        <table class="w-full text-sm text-left rtl:text-right text-body">
            <thead class="bg-neutral-secondary-soft border-b border-default">
                <tr>
                    <th scope="col" class="px-6 py-3 font-medium">No</th>
                    <th scope="col" class="px-6 py-3 font-medium">Nama Kategori</th>
                    <th scope="col" class="px-6 py-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kategoris as $kategori)
                    <tr class="odd:bg-neutral-primary even:bg-neutral-secondary-soft border-b border-default">
                        <td class="px-6 py-4">{{ $loop->iteration + $kategoris->firstItem() - 1 }}</td>
                        <td class="px-6 py-4">{{ $kategori->nama_kategori }}</td>
                        <td class="px-6 py-4">
                            <button onclick="openEditModal({{ $kategori->id }}, '{{ addslashes($kategori->nama_kategori) }}')" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Edit</button>
                            
                            <form action="{{ route('admin.kategori.destroy', $kategori->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded" onclick="return confirm('Hapus kategori ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $kategoris->links() }}
    </div>

    <!-- Modal Tambah -->
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50" id="modal">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h2 class="text-lg font-medium mb-4">Tambah Kategori</h2>
            <form action="{{ route('admin.kategori.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="nama_kategori" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="add_nama_kategori" required class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="flex justify-end">
                    <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded mr-2" onclick="closeModal()">Batal</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50" id="editModal">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h2 class="text-lg font-medium mb-4">Edit Kategori</h2>
            
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="edit_nama_kategori" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="edit_nama_kategori" required class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="flex justify-end">
                    <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded mr-2" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Logika Add Modal
        function openModal() {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('add_nama_kategori').value = ''; // Kosongkan input
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

        // Logika Edit Modal (PERBAIKAN UTAMA)
        function openEditModal(id, nama) {
            // 1. Tampilkan modal
            document.getElementById('editModal').classList.remove('hidden');
            
            // 2. Isi kotak input dengan nama kategori yang diklik
            document.getElementById('edit_nama_kategori').value = nama;
            
            // 3. Ubah URL form "action" agar menuju ID yang benar
            let url = "{{ route('admin.kategori.update', ':id') }}";
            url = url.replace(':id', id);
            document.getElementById('editForm').action = url;
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
@endsection