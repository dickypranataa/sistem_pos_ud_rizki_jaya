<aside class="w-64 bg-white border-r border-gray-200 hidden md:block flex-shrink-0">
    <div class="h-16 flex items-center justify-center border-b border-gray-200 bg-blue-600">
        <h2 class="text-xl font-bold text-white tracking-wider">UD RIZKI JAYA</h2>
    </div>

    <nav class="mt-5 px-4 space-y-2">
        
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-150 
           {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Dashboard
        </a>

        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-4 mb-1 pl-4">User</div>

        <a href="{{ route('admin.user.index') }}" 
           class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-150 text-gray-700 hover:bg-gray-100">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            Data User
        </a>

        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-4 mb-1 pl-4">Master Data</div>

        
        <a href="{{ route('admin.produk.index') }}" 
           class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-150 text-gray-700 hover:bg-gray-100">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            Data Produk
        </a>

        <a href="{{ route('admin.kategori.index') }}" 
           class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-150 text-gray-700 hover:bg-gray-100">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            Kategori
        </a>

        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-4 mb-1 pl-4">Transaksi & Laporan</div>

        <a href="#" 
           class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-150 text-gray-700 hover:bg-gray-100">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Riwayat Transaksi
        </a>

        <a href="#" 
           class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-150 bg-purple-50 text-purple-700 hover:bg-purple-100">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            Prediksi Stok
        </a>
    </nav>
</aside>