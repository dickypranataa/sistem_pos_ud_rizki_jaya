{{-- Panggil Layout Admin --}}
@extends('layouts.admin')

{{-- Isi Kontennya di sini --}}
@section('content')
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Selamat Datang, Admin!</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
               📦 </div>
            <div>
                <div class="text-gray-500 text-sm font-medium">Total Produk</div>
                <div class="text-3xl font-bold text-gray-800">{{ $produks }}</div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
               💰
            </div>
            <div>
                <div class="text-gray-500 text-sm font-medium">Penjualan Hari Ini</div>
                <div class="text-3xl font-bold text-gray-800">Rp 1.500.000</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center">
             <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
               ⚠️
            </div>
            <div>
                <div class="text-gray-500 text-sm font-medium">Stok Menipis</div>
                <div class="text-3xl font-bold text-gray-800">12 Item</div>
            </div>
        </div>
    </div>
@endsection