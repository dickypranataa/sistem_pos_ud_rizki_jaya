<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Kasir - UD Rizki Jaya</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans antialiased h-screen overflow-hidden flex flex-col">

    <header class="bg-blue-700 text-white shadow-md h-16 flex-shrink-0 flex items-center justify-between px-6 z-10">
        <div class="flex items-center gap-4">
            <h1 class="text-xl font-bold tracking-wider">POS KASIR</h1>
            <span class="bg-blue-800 px-3 py-1 rounded-full text-xs font-semibold">UD Rizki Jaya</span>
        </div>

        <div class="flex items-center gap-4">
            <span class="text-sm font-medium">Kasir: {{ Auth::user()->name ?? 'Demo' }}</span>
            <span class="text-blue-300">|</span>
            <a href="{{ route('admin.dashboard') }}" class="text-sm hover:text-blue-200 transition flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Keluar POS
            </a>
        </div>
    </header>

    <main class="flex-1 overflow-hidden">
        {{ $slot }}
    </main>

</body>
</html>