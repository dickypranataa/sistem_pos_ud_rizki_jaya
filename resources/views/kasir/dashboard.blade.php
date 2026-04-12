@extends('layouts.kasir')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 bg-gray-50/50 min-h-screen">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Selamat Bertugas, {{ Auth::user()->name }}!</h2>
                <p class="text-sm text-gray-500 mt-1">Sistem POS UD Rizki Jaya sudah siap digunakan untuk melayani pelanggan
                    hari ini.</p>
            </div>

            <a href="{{ route('kasir.transaksi') }}"
                class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                Buka Mesin Kasir
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="p-4 bg-emerald-50 text-emerald-600 rounded-lg mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Transaksi Anda Hari Ini</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $transaksiSaya }} <span
                            class="text-base font-medium text-gray-500">Struk</span></h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="p-4 bg-indigo-50 text-indigo-600 rounded-lg mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Tanggal Operasional</p>
                    <h3 class="text-xl font-bold text-gray-900">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="p-4 bg-emerald-50 text-emerald-600 rounded-lg mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-cash-stack" viewBox="0 0 16 16">
                        <path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1zm7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4" />
                        <path
                            d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Omzet Anda Hari Ini</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $omzetHariIni }} <span
                            class="text-base font-medium text-gray-500">Rupiah</span></h3>
                </div>
            </div>

        </div>
    </div>
@endsection