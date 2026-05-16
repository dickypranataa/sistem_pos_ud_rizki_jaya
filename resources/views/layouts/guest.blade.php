<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="icon" type="image/png" href="{{ asset('images/logo_udrj.png') }}">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-blue-600">

        <!-- Decorative Blur Circle -->
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-white/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-pink-400/20 rounded-full blur-3xl animate-pulse"></div>

        <div
            class="relative w-full max-w-5xl mx-4 bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

            <!-- Left Side (Branding - Desktop Only) -->
            <div
                class="hidden md:flex flex-col justify-center items-center text-white p-12 bg-white/10 backdrop-blur-xl">
                <img src="{{ asset('images/logo_udrj.png') }}" class="w-24 mb-6 drop-shadow-lg">
                <h1 class="text-3xl font-bold mb-3">UD Rizki Jaya</h1>
                <p class="text-white/80 text-center text-sm leading-relaxed">
                    Sistem POS modern untuk manajemen penjualan,
                    stok barang, dan laporan transaksi secara real-time.
                </p>
            </div>

            <!-- Right Side (Form) -->
            <div class="bg-white p-8 md:p-12">
                {{ $slot }}
            </div>

        </div>
    </div>
</body>

</html>