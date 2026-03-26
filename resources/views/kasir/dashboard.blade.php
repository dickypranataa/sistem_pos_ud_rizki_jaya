<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Kasir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 text-gray-900 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Selamat Bertugas, {{ Auth::user()->name }}!</h3>
                        <p class="text-sm text-gray-500 mt-1">Sistem POS UD Rizki Jaya sudah siap digunakan untuk melayani transaksi pelanggan hari ini.</p>
                    </div>
                    
                    <a href="{{ route('kasir.transaksi') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm w-full sm:w-auto justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Buka Mesin Kasir
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center">
                    <div class="p-3 rounded-full bg-emerald-50 text-emerald-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Transaksi Anda Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">
                            {{ $transaksiSaya }} <span class="text-sm font-normal text-gray-500">Struk</span>
                        </p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center">
                    <div class="p-3 rounded-full bg-indigo-50 text-indigo-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Tanggal Operasional</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">
                            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>