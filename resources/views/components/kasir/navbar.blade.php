<header class="bg-white shadow h-16 flex items-center justify-between px-6 z-10">
    
    <div class="flex items-center">
        <button class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <h1 class="text-lg font-semibold text-gray-700 ml-4 hidden md:block">
            Panel Administrator
        </h1>
    </div>

    <div class="flex items-center gap-6"> 
        
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="relative p-2 text-gray-500 hover:text-gray-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>

                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </button>

            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50 overflow-hidden" style="display: none;">
                <div class="flex justify-between items-center px-4 py-3 bg-gray-50 border-b">
                    <span class="font-bold text-gray-700">Notifikasi</span>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <a href="{{ route('admin.notifikasi.readAll') }}" class="text-xs text-blue-500 hover:text-blue-700">Tandai sudah dibaca</a>
                    @endif
                </div>
                
                <div class="max-h-80 overflow-y-auto">
                    @forelse(auth()->user()->unreadNotifications as $notif)
                        <a href="{{ $notif->data['url'] }}" class="block px-4 py-3 border-b hover:bg-gray-50 transition">
                            <p class="text-sm font-bold {{ $notif->data['warna'] }}">{{ $notif->data['judul'] }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ $notif->data['pesan'] }}</p>
                            <p class="text-[10px] text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                        </a>
                    @empty
                        <div class="px-4 py-6 text-center text-sm text-gray-500">
                            Tidak ada notifikasi baru.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                <span class="mr-2">{{ Auth::user()->name }}</span>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="open" 
                 @click.away="open = false"
                 class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-md shadow-lg py-1 z-50"
                 style="display: none;">
                 
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                        Logout
                    </button>
                </form>
            </div>
        </div>

    </div>
</header>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>