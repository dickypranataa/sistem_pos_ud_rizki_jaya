<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'UD Rizki Jaya') }} - Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_udrj.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-900">

    <div class="min-h-screen flex">

        @include('components.admin.sidebar')

        <div class="flex-1 flex flex-col min-w-0">

            @include('components.admin.navbar')

            <main class="flex-1 p-6 overflow-y-auto">
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="bg-white border-t border-gray-200 p-4 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} UD Rizki Jaya - Sistem POS Cerdas
            </footer>
        </div>
    </div>
    <!-- Script untuk Toggle Sidebar Mobile -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnHamburger = document.getElementById('btn-hamburger');
            const sidebarMenu = document.getElementById('sidebar-menu');

            if (btnHamburger && sidebarMenu) {
                // Munculkan/Sembunyikan saat tombol diklik
                btnHamburger.addEventListener('click', function (e) {
                    e.stopPropagation(); // Mencegah klik bocor
                    sidebarMenu.classList.toggle('-translate-x-full');
                });

                // Tutup sidebar jika user klik di luar area sidebar
                document.addEventListener('click', function (e) {
                    if (!sidebarMenu.contains(e.target) && !btnHamburger.contains(e.target)) {
                        sidebarMenu.classList.add('-translate-x-full');
                    }
                });
            }
        });
    </script>
</body>

</html>