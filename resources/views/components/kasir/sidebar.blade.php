<aside id="sidebar-menu" class="w-64 bg-white border-r border-gray-200 flex-shrink-0 fixed inset-y-0 left-0 z-50 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 ease-in-out">
    <div class="h-16 flex items-center justify-center border-b border-gray-200 bg-blue-600">
        <h2 class="text-xl font-bold text-white tracking-wider">UD RIZKI JAYA</h2>
    </div>

    <nav class="mt-5 px-4 space-y-2">
        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-4 mb-1 pl-4">Menu Utama</div>

        <a href="{{ route('kasir.dashboard') }}"
            class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-150 
           {{ request()->routeIs('kasir.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('kasir.transaksi') }}"
            class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-150 text-gray-700 hover:bg-gray-100">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Mesin Kasir
        </a>

        <a href="{{ route('kasir.riwayat.index') }}"
            class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-150 text-gray-700 hover:bg-gray-100">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Riwayat Transaksi
        </a>
    </nav>
</aside>