<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Logo & Header --}}
    <div class="mb-8 text-center sm:text-left">
        <img src="{{ asset('images/logo_udrj.png') }}" class="w-16 h-16 sm:mx-0 mx-auto mb-4 object-contain">
        <h2 class="text-2xl font-normal text-gray-900 tracking-tight">Masuk</h2>
        <p class="text-sm text-gray-600 mt-2">Menggunakan akun sistem POS UD Rizki Jaya</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        {{-- Email --}}
        <div>
            <div class="relative">
                <input id="email"
                    class="block w-full px-4 py-3.5 border border-gray-300 rounded-lg text-base focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all placeholder:text-gray-400 bg-transparent text-gray-900"
                    type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    placeholder="Alamat email" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div>
            <div class="relative">
                <input id="password"
                    class="block w-full px-4 py-3.5 border border-gray-300 rounded-lg text-base focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all placeholder:text-gray-400 bg-transparent text-gray-900"
                    type="password" name="password" required autocomplete="current-password"
                    placeholder="Masukkan kata sandi" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Remember Me --}}
        <div>
            <label for="remember_me" class="inline-flex items-center cursor-pointer gap-2.5">
                <input id="remember_me" type="checkbox"
                    class="w-4.5 h-4.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
                    name="remember">
                <span class="text-sm text-gray-600 select-none">Ingat saya di perangkat ini</span>
            </label>
        </div>

        {{-- Submit & Links --}}
        <div class="flex items-center justify-end pt-4">
            <button type="submit"
                class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-sm rounded-full transition-all duration-150 shadow-sm hover:shadow">
                Masuk
            </button>
        </div>
    </form>
</x-guest-layout>