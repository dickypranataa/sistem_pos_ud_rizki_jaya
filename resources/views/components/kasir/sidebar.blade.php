<aside id="sidebar-menu"
    class="w-64 bg-white border-r border-gray-100 flex-shrink-0 fixed inset-y-0 left-0 z-50 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 ease-in-out shadow-lg md:shadow-sm flex flex-col">

    {{-- Logo Area --}}
    <div class="h-16 flex items-center px-5 border-b border-gray-100 bg-gradient-to-r from-blue-600 to-blue-700 flex-shrink-0">
        <div class="flex items-center gap-3 w-full">
            <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                <img src="{{ asset('images/logo_udrj.png') }}" alt="Logo UD Rizki Jaya"
                     class="w-7 h-7 object-contain">
            </div>
            <div>
                <h2 class="text-sm font-bold text-white tracking-wider leading-none">UD RIZKI JAYA</h2>
                <p class="text-[10px] text-blue-200 mt-0.5 font-medium">Sistem POS Kasir</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5">

        <div class="pt-0 pb-1.5 px-3">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Menu Utama</span>
        </div>

        <a href="{{ route('kasir.dashboard') }}"
            class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group
           {{ request()->routeIs('kasir.dashboard') 
              ? 'bg-blue-600 text-white shadow-md shadow-blue-200' 
              : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-200
                {{ request()->routeIs('kasir.dashboard') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-blue-50' }}">
                <svg class="w-4 h-4 {{ request()->routeIs('kasir.dashboard') ? 'text-white' : 'text-gray-500 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
            </span>
            Dashboard
        </a>

        <a href="{{ route('kasir.transaksi') }}"
            class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group
           {{ request()->routeIs('kasir.transaksi') 
              ? 'bg-blue-600 text-white shadow-md shadow-blue-200' 
              : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-200
                {{ request()->routeIs('kasir.transaksi') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-blue-50' }}">
                <svg class="w-4 h-4 {{ request()->routeIs('kasir.transaksi') ? 'text-white' : 'text-gray-500 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </span>
            Mesin Kasir
        </a>

        <a href="{{ route('kasir.riwayat.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group
           {{ request()->routeIs('kasir.riwayat.*') 
              ? 'bg-blue-600 text-white shadow-md shadow-blue-200' 
              : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-200
                {{ request()->routeIs('kasir.riwayat.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-blue-50' }}">
                <svg class="w-4 h-4 {{ request()->routeIs('kasir.riwayat.*') ? 'text-white' : 'text-gray-500 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </span>
            Riwayat Transaksi
        </a>
    </nav>

    {{-- Footer Sidebar --}}
    <div class="p-3 border-t border-gray-100 flex-shrink-0">
        <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-emerald-50">
            <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] font-semibold text-emerald-700 truncate">Kasir Aktif</p>
                <p class="text-[10px] text-emerald-500">{{ Auth::user()->name }}</p>
            </div>
        </div>
    </div>
</aside>