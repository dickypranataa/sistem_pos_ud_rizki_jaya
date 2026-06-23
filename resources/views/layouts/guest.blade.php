<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans for Google-like premium typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/logo_udrj.png') }}">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-[#1f1f1f] bg-blue-600 min-h-screen flex items-center justify-center p-4 sm:p-6" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    <div class="w-full flex flex-col items-center">
        <!-- Google-inspired login card container with fully responsive styles -->
        <div class="w-full max-w-[448px] bg-white rounded-3xl sm:rounded-[28px] p-6 sm:p-10 shadow-2xl">
            {{ $slot }}
        </div>
    </div>
</body>

</html>