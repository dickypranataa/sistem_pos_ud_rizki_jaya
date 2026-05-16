<header class="bg-white border-b border-gray-100 h-16 flex items-center justify-between px-5 z-10 flex-shrink-0 shadow-sm">

    <div class="flex items-center gap-3">
        <button id="btn-hamburger" class="md:hidden w-9 h-9 rounded-xl bg-gray-50 hover:bg-gray-100 text-gray-500 hover:text-gray-700 focus:outline-none flex items-center justify-center transition-all duration-200 active:scale-95">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
        </button>
        <div>
            <h1 class="text-sm font-bold text-gray-900 hidden md:block">Panel Kasir</h1>
            <p class="text-xs text-gray-400 hidden md:block">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
    </div>

    <div class="flex items-center gap-2">

        {{-- Notifikasi --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                class="relative w-9 h-9 rounded-xl bg-gray-50 hover:bg-gray-100 text-gray-500 hover:text-gray-700 flex items-center justify-center transition-all duration-200 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>

                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
                @endif
            </button>

            <div x-show="open" @click.away="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden"
                style="display: none;">
                <div class="flex justify-between items-center px-4 py-3 border-b border-gray-100 bg-gray-50/80">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-gray-800 text-sm">Notifikasi</span>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="text-[10px] font-bold bg-red-500 text-white px-1.5 py-0.5 rounded-full">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </div>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <a href="{{ route('notifikasi.readAll') }}" class="text-xs text-blue-500 hover:text-blue-700 font-semibold transition-colors">
                            Tandai dibaca</a>
                    @endif
                </div>

                <div class="max-h-80 overflow-y-auto">
                    @forelse(auth()->user()->unreadNotifications as $notif)
                        <a href="{{ $notif->data['url'] }}" class="flex gap-3 px-4 py-3 border-b border-gray-50 hover:bg-blue-50/50 transition-colors duration-150">
                            <div class="w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold {{ $notif->data['warna'] }} leading-snug">{{ $notif->data['judul'] }}</p>
                                <p class="text-xs text-gray-500 mt-0.5 leading-snug line-clamp-2">{{ $notif->data['pesan'] }}</p>
                                <p class="text-[10px] text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                        </a>
                    @empty
                        <div class="px-4 py-8 text-center">
                            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>
                            <p class="text-xs font-medium text-gray-400">Tidak ada notifikasi baru.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Divider --}}
        <div class="w-px h-5 bg-gray-200 mx-1"></div>

        {{-- User Dropdown --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                class="flex items-center gap-2.5 pl-1 pr-3 py-1.5 rounded-xl hover:bg-gray-50 transition-all duration-200 active:scale-95 focus:outline-none">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white text-xs font-bold shadow-sm flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div class="hidden sm:block text-left">
                    <p class="text-xs font-bold text-gray-800 leading-none">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Kasir</p>
                </div>
                <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="open" @click.away="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                class="absolute right-0 mt-2 w-52 bg-white border border-gray-100 rounded-2xl shadow-xl py-1.5 z-50"
                style="display: none;">

                <div class="px-4 py-2.5 border-b border-gray-100 mb-1">
                    <p class="text-xs font-bold text-gray-800">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-gray-400 mt-0.5 truncate">{{ Auth::user()->email }}</p>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="px-1.5">
                    @csrf
                    <button type="submit"
                        class="w-full text-left flex items-center gap-2.5 px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-xl transition-colors duration-150 font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>

    </div>
</header>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>