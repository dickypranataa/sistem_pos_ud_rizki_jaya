<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'UD Rizki Jaya') }} - Kasir</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_udrj.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-900">

    <div class="min-h-screen flex">

        @if(!isset($hideSidebar) || !$hideSidebar)
            @include('components.kasir.sidebar')
        @endif

        <div class="flex-1 flex flex-col min-w-0">

            @if(!isset($hideNavbar) || !$hideNavbar)
                @include('components.kasir.navbar')
            @endif

            <main class="flex-1 {{ (isset($isFullScreen) && $isFullScreen) ? 'p-0' : 'p-6' }} overflow-y-auto">
                @if (session('success'))
                    <div
                        class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
                {{ $slot ?? '' }}
            </main>

            @if(!isset($hideFooter) || !$hideFooter)
                <footer class="bg-white border-t border-gray-200 p-4 text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} UD Rizki Jaya - Sistem POS Cerdas
                </footer>
            @endif
        </div>
    </div>

    @livewireScripts
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