@extends('layouts.kasir')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-slate-50 px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <p class="text-sm font-medium text-blue-500 mb-1">👋 Selamat datang kembali</p>
            <h2 class="text-2xl font-bold text-gray-900">{{ Auth::user()->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">Sistem POS UD Rizki Jaya siap melayani pelanggan hari ini.</p>
        </div>
        <a href="{{ route('kasir.transaksi') }}"
            class="inline-flex items-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            Buka Mesin Kasir
        </a>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

        {{-- Transaksi --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 flex items-center gap-5 shadow-sm hover:shadow-md transition">
            <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Transaksi Hari Ini</p>
                <div class="flex items-baseline gap-1.5">
                    <h3 class="text-3xl font-bold text-gray-900">{{ $transaksiSaya }}</h3>
                    <span class="text-sm font-medium text-gray-400">struk</span>
                </div>
            </div>
        </div>

        {{-- Tanggal --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 flex items-center gap-5 shadow-sm hover:shadow-md transition">
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Tanggal Operasional</p>
                <h3 class="text-base font-bold text-gray-900 leading-snug">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </h3>
            </div>
        </div>

        {{-- Omzet --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 flex items-center gap-5 shadow-sm hover:shadow-md transition sm:col-span-2 lg:col-span-1">
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Omzet Hari Ini</p>
                <div class="flex items-baseline gap-1.5">
                    <h3 class="text-2xl font-bold text-gray-900">{{ $omzetHariIni }}</h3>
                    <span class="text-sm font-medium text-gray-400">Rp</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection