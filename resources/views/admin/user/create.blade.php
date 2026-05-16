@extends('layouts.admin')

@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Tambah User</h2>
    <p class="text-sm text-gray-400 mt-1 font-medium">Buat akun pengguna baru untuk mengakses sistem.</p>
</div>

<div class="max-w-lg">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-bold text-gray-700 mb-5 pb-3 border-b border-gray-100 flex items-center gap-2">
            <span class="w-6 h-6 rounded-lg bg-blue-50 flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </span>
            Informasi Akun
        </h3>

        <form action="{{ route('admin.user.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white transition-all duration-200"
                        placeholder="Nama lengkap pengguna">
                    <!-- Menampilkan error untuk name -->
                    @error('name')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white transition-all duration-200"
                        placeholder="email@domain.com">
                    <!-- Menampilkan error untuk email (misal: duplikat) -->
                    @error('email')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white transition-all duration-200"
                        placeholder="Minimal 8 karakter">
                    <!-- Menampilkan error untuk password (misal: kurang dari 8 karakter) -->
                    @error('password')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Role</label>
                    <select name="role" id="role" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white transition-all duration-200">
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-gray-100">
                <a href="{{ route('admin.user.index') }}"
                    class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 active:scale-95 text-gray-700 text-sm font-semibold rounded-xl transition-all duration-200">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-md shadow-blue-200 hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Tambah User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection