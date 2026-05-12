<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

{{-- Logo & Header --}}
<div class="mb-8 text-center">
    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
    </div>
    <h2 class="text-xl font-bold text-gray-900">UD Rizki Jaya</h2>
    <p class="text-sm text-gray-500 mt-1">Silakan masuk untuk mengakses sistem POS</p>
</div>

<form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf

    {{-- Email --}}
    <div>
        <x-input-label for="email" value="Alamat Email" class="text-gray-700 font-medium text-sm mb-1.5" />
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <x-text-input id="email"
                class="block w-full pl-10 border-gray-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm bg-gray-50 text-sm"
                type="email" name="email" :value="old('email')"
                required autofocus autocomplete="username"
                placeholder="admin@udrizkijaya.com" />
        </div>
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    {{-- Password --}}
    <div>
        <div class="flex justify-between items-center mb-1.5">
            <x-input-label for="password" value="Kata Sandi" class="text-gray-700 font-medium text-sm" />
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                    Lupa sandi?
                </a>
            @endif
        </div>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <x-text-input id="password"
                class="block w-full pl-10 border-gray-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm bg-gray-50 text-sm"
                type="password" name="password"
                required autocomplete="current-password"
                placeholder="••••••••" />
        </div>
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    {{-- Remember Me --}}
    <div>
        <label for="remember_me" class="inline-flex items-center cursor-pointer gap-2">
            <input id="remember_me" type="checkbox"
                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 cursor-pointer"
                name="remember">
            <span class="text-sm text-gray-600 select-none">Ingat saya di perangkat ini</span>
        </label>
    </div>

    {{-- Submit --}}
    <div class="pt-2">
        <button type="submit"
            class="w-full flex justify-center items-center gap-2 px-4 py-3 bg-blue-600 border border-transparent rounded-xl font-semibold text-sm text-white tracking-wide hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-md hover:shadow-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            Masuk ke Sistem
        </button>
    </div>
</form>
</x-guest-layout>