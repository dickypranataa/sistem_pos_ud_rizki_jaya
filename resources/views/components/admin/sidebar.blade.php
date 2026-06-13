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
                <p class="text-[10px] text-blue-200 mt-0.5 font-medium">Sistem POS Admin</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5">

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group
           {{ request()->routeIs('admin.dashboard') 
              ? 'bg-blue-600 text-white shadow-md shadow-blue-200' 
              : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-200
                {{ request()->routeIs('admin.dashboard') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-blue-50' }}">
                <svg class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-500 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                    </path>
                </svg>
            </span>
            Dashboard
        </a>

        {{-- Section: User --}}
        <div class="pt-4 pb-1.5 px-3">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">User</span>
        </div>

        <a href="{{ route('admin.user.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group
           {{ request()->routeIs('admin.user.*') 
              ? 'bg-blue-600 text-white shadow-md shadow-blue-200' 
              : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-200
                {{ request()->routeIs('admin.user.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-blue-50' }}">
                <svg class="w-4 h-4 {{ request()->routeIs('admin.user.*') ? 'text-white' : 'text-gray-500 group-hover:text-blue-600' }}" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                </svg>
            </span>
            Data User
        </a>

        {{-- Section: Master Data --}}
        <div class="pt-4 pb-1.5 px-3">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Master Data</span>
        </div>

        <a href="{{ route('admin.produk.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group
           {{ request()->routeIs('admin.produk.*') 
              ? 'bg-blue-600 text-white shadow-md shadow-blue-200' 
              : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-200
                {{ request()->routeIs('admin.produk.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-blue-50' }}">
                <svg class="w-4 h-4 {{ request()->routeIs('admin.produk.*') ? 'text-white' : 'text-gray-500 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </span>
            Data Produk
        </a>

        <a href="{{ route('admin.koreksi.create') }}"
            class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group
           {{ request()->routeIs('admin.koreksi.*') 
              ? 'bg-blue-600 text-white shadow-md shadow-blue-200' 
              : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-200
                {{ request()->routeIs('admin.koreksi.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-blue-50' }}">
                <svg class="w-4 h-4 {{ request()->routeIs('admin.koreksi.*') ? 'text-white' : 'text-gray-500 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                    </path>
                </svg>
            </span>
            Koreksi Stok
        </a>

        <a href="{{ route('admin.kategori.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group
           {{ request()->routeIs('admin.kategori.*') 
              ? 'bg-blue-600 text-white shadow-md shadow-blue-200' 
              : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-200
                {{ request()->routeIs('admin.kategori.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-blue-50' }}">
                <svg class="w-4 h-4 {{ request()->routeIs('admin.kategori.*') ? 'text-white' : 'text-gray-500 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                    </path>
                </svg>
            </span>
            Kategori
        </a>

        {{-- Section: Transaksi & Laporan --}}
        <div class="pt-4 pb-1.5 px-3">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Transaksi & Laporan</span>
        </div>

        <a href="{{ route('admin.pembayaran.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group
           {{ request()->routeIs('admin.pembayaran.*') 
              ? 'bg-blue-600 text-white shadow-md shadow-blue-200' 
              : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-200
                {{ request()->routeIs('admin.pembayaran.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-blue-50' }}">
                <svg class="w-4 h-4 {{ request()->routeIs('admin.pembayaran.*') ? 'text-white' : 'text-gray-500 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                    </path>
                </svg>
            </span>
            Metode Pembayaran
        </a>

        <a href="{{ route('admin.riwayat.index')}}"
            class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group
           {{ request()->routeIs('admin.riwayat.*') 
              ? 'bg-blue-600 text-white shadow-md shadow-blue-200' 
              : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-200
                {{ request()->routeIs('admin.riwayat.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-blue-50' }}">
                <svg class="w-4 h-4 {{ request()->routeIs('admin.riwayat.*') ? 'text-white' : 'text-gray-500 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4">
                    </path>
                </svg>
            </span>
            Riwayat Stok
        </a>

        <a href="{{ route('admin.transaksi.index')}}"
            class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group
           {{ request()->routeIs('admin.transaksi.*') 
              ? 'bg-blue-600 text-white shadow-md shadow-blue-200' 
              : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-200
                {{ request()->routeIs('admin.transaksi.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-blue-50' }}">
                <svg class="w-4 h-4 {{ request()->routeIs('admin.transaksi.*') ? 'text-white' : 'text-gray-500 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>
            </span>
            Riwayat Transaksi
        </a>

        <a href="{{ route('admin.piutang.index')}}"
            class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group
           {{ request()->routeIs('admin.piutang.*') 
              ? 'bg-blue-600 text-white shadow-md shadow-blue-200' 
              : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-200
                {{ request()->routeIs('admin.piutang.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-blue-50' }}">
                <svg class="w-4 h-4 {{ request()->routeIs('admin.piutang.*') ? 'text-white' : 'text-gray-500 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
            </span>
            Manajemen Piutang
        </a>
    </nav>

    {{-- Footer Sidebar --}}
    <div class="p-3 border-t border-gray-100 flex-shrink-0">
        <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-gray-50">
            <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] font-semibold text-gray-700 truncate">Asisten AI Aktif</p>
                <p class="text-[10px] text-gray-400">Powered by Gemini</p>
            </div>
        </div>
    </div>
</aside>