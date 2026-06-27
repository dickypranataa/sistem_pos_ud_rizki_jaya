@extends('layouts.admin')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-7">
    <div>
        <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Manajemen User</h2>
        <p class="text-sm text-gray-400 mt-1 font-medium">Kelola akun dan hak akses pengguna sistem.</p>
    </div>
    <a href="{{ route('admin.user.create') }}"
        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white py-2.5 px-5 rounded-2xl font-semibold text-sm shadow-md shadow-blue-200 transition-all duration-200 hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
        </svg>
        Tambah User
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($users as $user)
                <tr class="hover:bg-slate-50/70 transition-colors duration-150">
                    <td class="px-6 py-4 text-gray-400 text-sm font-medium">
                        {{ $loop->iteration + $users->firstItem() - 1 }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            {{-- Avatar inisial --}}
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-sm">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <span class="font-semibold text-gray-900">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-sm">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        @if($user->role === 'admin')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg border border-blue-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 inline-block"></span>
                                Admin
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-lg border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                                Kasir
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.user.edit', $user->id) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 active:scale-95 text-blue-700 text-xs font-semibold rounded-lg transition-all duration-150">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            <form id="delete-form-{{ $user->id }}" action="{{ route('admin.user.destroy', $user->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 active:scale-95 text-red-700 text-xs font-semibold rounded-lg transition-all duration-150">
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

    {{-- Pagination --}}
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
        {{ $users->links() }}
    </div>
</div>

<script>
    function confirmDelete(id, namaUser) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "User '" + namaUser + "' akan dihapus permanen!",
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