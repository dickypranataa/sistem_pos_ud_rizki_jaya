@extends('layouts.admin')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 bg-gray-50/50 min-h-screen">

        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Dashboard Analitik</h2>
            <p class="text-sm text-gray-500 mt-1">Ringkasan performa penjualan dan pergerakan stok UD Rizki Jaya.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="p-4 bg-indigo-50 text-indigo-600 rounded-lg mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Omzet Bulan Ini</p>
                    <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($omzetBulanIni, 0, ',', '.') }}</h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="p-4 bg-emerald-50 text-emerald-600 rounded-lg mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Transaksi</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalTransaksiBulanIni, 0, ',', '.') }}
                        Nota</h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
                <div class="p-4 bg-red-50 text-red-600 rounded-lg mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Peringatan Stok Kritis</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stokKritis }} Produk</h3>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Tren Pendapatan (7 Hari Terakhir)</h3>
            <div id="chart-omzet" class="w-full"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">5 Produk Terlaris Bulan Ini</h3>
                <div id="chart-produk" class="w-full"></div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Komposisi Metode Pembayaran</h3>
                <div id="chart-pembayaran" class="w-full flex justify-center"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">5 Transaksi Terakhir</h3>
                    <a href="#" class="text-sm text-indigo-600 font-semibold hover:text-indigo-800">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="px-4 py-3 rounded-tl-lg">Kode</th>
                                <th class="px-4 py-3">Waktu</th>
                                <th class="px-4 py-3 text-right">Total</th>
                                <th class="px-4 py-3 rounded-tr-lg">Bayar Via</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($transaksiTerakhir as $trx)
                                <tr class="hover:bg-gray-50/50">
                                    <td class="px-4 py-3 font-medium text-indigo-600">{{ $trx->kode_transaksi }}</td>
                                    <td class="px-4 py-3 text-gray-500">
                                        {{ \Carbon\Carbon::parse($trx->waktu_transaksi)->translatedFormat('d M, H:i') }}</td>
                                    <td class="px-4 py-3 text-right font-bold text-gray-900">Rp
                                        {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded font-semibold">{{ $trx->pembayaran->nama_pembayaran ?? '-' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-gray-500">Belum ada transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Riwayat Stok Terakhir</h3>
                    <a href="{{ route('admin.riwayat.index') }}"
                        class="text-sm text-indigo-600 font-semibold hover:text-indigo-800">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="px-4 py-3 rounded-tl-lg">Produk</th>
                                <th class="px-4 py-3">Jenis</th>
                                <th class="px-4 py-3 text-center">Qty</th>
                                <th class="px-4 py-3 text-center rounded-tr-lg">Sisa</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($stokTerakhir as $stok)
                                <tr class="hover:bg-gray-50/50">
                                    <td class="px-4 py-3 font-medium text-gray-900 truncate max-w-[150px]">
                                        {{ $stok->produk->nama_produk ?? 'Dihapus' }}</td>
                                    <td class="px-4 py-3">
                                        @if($stok->tipe === 'sale') <span
                                            class="text-red-500 font-semibold text-xs">Penjualan</span>
                                        @elseif($stok->tipe === 'restock') <span
                                            class="text-emerald-500 font-semibold text-xs">Restock</span>
                                        @else <span class="text-amber-500 font-semibold text-xs">Koreksi</span> @endif
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-bold {{ $stok->jumlah < 0 ? 'text-red-600' : 'text-emerald-600' }}">
                                        {{ $stok->jumlah > 0 ? '+' : '' }}{{ $stok->jumlah }}</td>
                                    <td class="px-4 py-3 text-center text-gray-600 font-semibold">{{ $stok->stok_akhir }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-gray-500">Belum ada pergerakan stok.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // 1. DATA PHP KE JAVASCRIPT
            const labelTanggal = @json($omzet7Hari->map(fn($item) => \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M')));
            const dataOmzet = @json($omzet7Hari->pluck('total'));

            const labelProduk = @json($produkTerlaris->map(fn($item) => $item->produk->nama_produk ?? 'Dihapus'));
            const dataProduk = @json($produkTerlaris->pluck('total_terjual'));

            const labelPembayaran = @json($metodePembayaran->map(fn($item) => $item->pembayaran->nama_pembayaran ?? 'Lainnya'));
            const dataPembayaran = @json($metodePembayaran->pluck('total'));


            // 2. RENDER GRAFIK TREN OMZET (AREA)
            new ApexCharts(document.querySelector("#chart-omzet"), {
                series: [{ name: 'Pendapatan (Rp)', data: dataOmzet }],
                chart: { type: 'area', height: 320, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                colors: ['#4F46E5'], // Indigo-600
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 100] } },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                xaxis: { categories: labelTanggal, tooltip: { enabled: false } },
                yaxis: { labels: { formatter: (value) => "Rp " + (value / 1000000).toFixed(1) + " Jt" } }
            }).render();

            // 3. RENDER GRAFIK PRODUK TERLARIS (BAR HORIZONTAL)
            new ApexCharts(document.querySelector("#chart-produk"), {
                series: [{ name: 'Terjual (Pcs)', data: dataProduk }],
                chart: { type: 'bar', height: 320, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: '50%' } },
                colors: ['#0EA5E9'], // Sky-500
                dataLabels: { enabled: true, style: { colors: ['#fff'] } },
                xaxis: { categories: labelProduk }
            }).render();

            // 4. RENDER GRAFIK METODE PEMBAYARAN (PIE/DONUT)
            new ApexCharts(document.querySelector("#chart-pembayaran"), {
                series: dataPembayaran,
                labels: labelPembayaran,
                chart: { type: 'donut', height: 320, fontFamily: 'Inter, sans-serif' },
                colors: ['#10B981', '#3B82F6', '#F59E0B', '#6366F1'],
                plotOptions: { pie: { donut: { size: '65%' } } },
                dataLabels: { enabled: true },
                legend: { position: 'bottom' }
            }).render();

        });
    </script>
@endsection