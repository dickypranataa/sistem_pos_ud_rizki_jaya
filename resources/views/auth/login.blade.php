<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-gray-800">UD Rizki Jaya</h2>
        <p class="text-sm text-gray-500 mt-1">Silakan masuk untuk mengakses sistem POS</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" value="Alamat Email" class="text-gray-700 font-medium" />
            
            <x-text-input id="email" class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" 
                          type="email" 
                          name="email" 
                          :value="old('email')" 
                          required autofocus autocomplete="username" 
                          placeholder="admin@udrizkijaya.com" />
                          
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-5">
            <div class="flex justify-between items-center mb-1">
                <x-input-label for="password" value="Kata Sandi" class="text-gray-700 font-medium" />
                
                @if (Route::has('password.request'))
                    <a class="text-sm text-blue-600 hover:text-blue-800 hover:underline focus:outline-none transition-colors" href="{{ route('password.request') }}">
                        Lupa sandi?
                    </a>
                @endif
            </div>

            <x-text-input id="password" class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                          type="password"
                          name="password"
                          required autocomplete="current-password" 
                          placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-5">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 cursor-pointer" name="remember">
                <span class="ms-2 text-sm text-gray-600 select-none">Ingat saya di perangkat ini</span>
            </label>
        </div>

        <div class="mt-8">
            <button type="submit" class="w-full flex justify-center items-center px-4 py-2.5 bg-blue-600 border border-transparent rounded-md font-semibold text-white tracking-wide hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 ease-in-out shadow-md hover:shadow-lg">
                Masuk ke Sistem
            </button>
        </div>
    </form>
</x-guest-layout>