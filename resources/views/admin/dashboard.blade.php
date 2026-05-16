@extends('layouts.admin')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <div class="min-h-screen">

        {{-- Page Header --}}
        <div class="mb-7 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-1">Overview</p>
                <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Dashboard Analitik</h2>
                <p class="text-sm text-gray-400 mt-1 font-medium">Ringkasan performa penjualan dan pergerakan stok UD Rizki Jaya.</p>
            </div>
            <div class="flex items-center gap-2 text-xs text-gray-400 bg-white rounded-xl px-3.5 py-2.5 border border-gray-100 shadow-sm">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="font-semibold text-gray-600">{{ now()->translatedFormat('d F Y') }}</span>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-7">

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 ease-in-out cursor-default">
                <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Total Omzet Bulan Ini</p>
                    <h3 class="text-xl font-extrabold text-gray-900 truncate">Rp {{ number_format($omzetBulanIni, 0, ',', '.') }}</h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 ease-in-out cursor-default">
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Total Transaksi</p>
                    <h3 class="text-xl font-extrabold text-gray-900">{{ number_format($totalTransaksiBulanIni, 0, ',', '.') }} <span class="text-sm font-semibold text-gray-400">Nota</span></h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 ease-in-out cursor-default">
                <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Peringatan Stok Kritis</p>
                    <h3 class="text-xl font-extrabold text-gray-900">{{ $stokKritis }} <span class="text-sm font-semibold text-gray-400">Produk</span></h3>
                </div>
            </div>

        </div>

        {{-- Charts Row 1: Omzet + AI --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Tren Pendapatan</h3>
                        <p class="text-xs text-gray-400 mt-0.5">7 Hari Terakhir</p>
                    </div>
                    <span class="text-xs text-blue-600 bg-blue-50 font-bold px-2.5 py-1 rounded-lg border border-blue-100">Area Chart</span>
                </div>
                <div id="chart-omzet" class="w-full"></div>
            </div>

            {{-- AI Assistant --}}
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md shadow-blue-200">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Asisten Bisnis AI</h3>
                        <p class="text-xs text-gray-400 font-medium">Powered by Gemini — Analisis real-time</p>
                    </div>
                </div>

                <div id="chat-box"
                    class="flex-1 overflow-y-auto bg-slate-50 rounded-2xl p-4 mb-4 border border-gray-100 flex flex-col gap-3 min-h-[220px] max-h-[320px]">
                    <div class="bg-white border border-gray-100 text-gray-800 p-3 rounded-r-2xl rounded-bl-2xl max-w-[90%] self-start text-sm shadow-sm leading-relaxed">
                        Halo bos! Saya adalah Asisten AI Anda. Data transaksi hari ini sudah saya rangkum. Ada yang ingin ditanyakan?
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 mb-3">
                    <button class="quick-prompt bg-blue-50 hover:bg-blue-100 active:scale-95 text-blue-700 text-xs font-semibold py-1.5 px-3 rounded-xl transition-all duration-150 border border-blue-100">Ringkasan hari ini</button>
                    <button class="quick-prompt bg-emerald-50 hover:bg-emerald-100 active:scale-95 text-emerald-700 text-xs font-semibold py-1.5 px-3 rounded-xl transition-all duration-150 border border-emerald-100">Cek stok kritis</button>
                    <button class="quick-prompt bg-amber-50 hover:bg-amber-100 active:scale-95 text-amber-700 text-xs font-semibold py-1.5 px-3 rounded-xl transition-all duration-150 border border-amber-100">Ide promosi</button>
                </div>

                <div class="flex gap-2 mt-auto">
                    <input type="text" id="ai-input"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 focus:bg-white placeholder-gray-400 transition-all duration-200"
                        placeholder="Tanyakan sesuatu...">
                    <button id="btn-send-ai"
                        class="bg-blue-600 hover:bg-blue-700 active:scale-95 text-white px-4 py-2.5 rounded-2xl text-sm font-semibold transition-all duration-200 shadow-md shadow-blue-200 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </div>
            </div>

        </div>

        {{-- Charts Row 2: Produk Terlaris + Pembayaran --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">5 Produk Terlaris</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Bulan Ini</p>
                    </div>
                    <span class="text-xs text-blue-600 bg-blue-50 font-bold px-2.5 py-1 rounded-lg border border-blue-100">Bar Chart</span>
                </div>
                <div id="chart-produk" class="w-full"></div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Komposisi Metode Pembayaran</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Bulan Ini</p>
                    </div>
                    <span class="text-xs text-blue-600 bg-blue-50 font-bold px-2.5 py-1 rounded-lg border border-blue-100">Donut</span>
                </div>
                <div id="chart-pembayaran" class="w-full flex justify-center"></div>
            </div>
        </div>

        {{-- Tables Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- 5 Transaksi Terakhir --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">5 Transaksi Terakhir</h3>
                        <p class="text-[11px] text-gray-400 mt-0.5">Update real-time</p>
                    </div>
                    <a href="{{ route('admin.transaksi.index') }}"
                        class="text-xs text-blue-600 font-bold hover:text-blue-800 bg-blue-50 hover:bg-blue-100 active:scale-95 px-3 py-1.5 rounded-xl transition-all duration-150 border border-blue-100">
                        Lihat Semua →
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Total</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Bayar Via</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-sm">
                            @forelse($transaksiTerakhir as $trx)
                                <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                                    <td class="px-5 py-3">
                                        <span class="font-mono text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg border border-blue-100">{{ $trx->kode_transaksi }}</span>
                                    </td>
                                    <td class="px-5 py-3 text-gray-400 text-xs whitespace-nowrap font-medium">
                                        {{ \Carbon\Carbon::parse($trx->waktu_transaksi)->translatedFormat('d M, H:i') }}
                                    </td>
                                    <td class="px-5 py-3 text-right font-bold text-gray-900 text-xs whitespace-nowrap">
                                        Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-3">
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-lg font-semibold">{{ $trx->pembayaran->nama_pembayaran ?? '-' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-gray-400 text-sm">Belum ada transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Riwayat Stok Terakhir --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Riwayat Stok Terakhir</h3>
                        <p class="text-[11px] text-gray-400 mt-0.5">Aktivitas stok terbaru</p>
                    </div>
                    <a href="{{ route('admin.riwayat.index') }}"
                        class="text-xs text-blue-600 font-bold hover:text-blue-800 bg-blue-50 hover:bg-blue-100 active:scale-95 px-3 py-1.5 rounded-xl transition-all duration-150 border border-blue-100">
                        Lihat Semua →
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Produk</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Qty</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Sisa</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-sm">
                            @forelse($stokTerakhir as $stok)
                                <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                                    <td class="px-5 py-3 font-medium text-gray-900 max-w-[130px] truncate text-xs">
                                        {{ $stok->produk->nama_produk ?? 'Dihapus' }}
                                    </td>
                                    <td class="px-5 py-3">
                                        @if($stok->tipe === 'sale')
                                            <span class="px-2 py-1 bg-red-50 text-red-600 text-[11px] font-bold rounded-lg border border-red-100">Penjualan</span>
                                        @elseif($stok->tipe === 'restock')
                                            <span class="px-2 py-1 bg-emerald-50 text-emerald-600 text-[11px] font-bold rounded-lg border border-emerald-100">Restock</span>
                                        @else
                                            <span class="px-2 py-1 bg-amber-50 text-amber-600 text-[11px] font-bold rounded-lg border border-amber-100">Koreksi</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-center font-bold text-sm {{ $stok->jumlah < 0 ? 'text-red-600' : 'text-emerald-600' }}">
                                        {{ $stok->jumlah > 0 ? '+' : '' }}{{ $stok->jumlah }}
                                    </td>
                                    <td class="px-5 py-3 text-center text-gray-600 font-semibold text-sm">{{ $stok->stok_akhir }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-gray-400 text-sm">Belum ada pergerakan stok.</td>
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
            const labelTanggal = @json($omzet7Hari->map(fn($item) => \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M')));
            const dataOmzet = @json($omzet7Hari->pluck('total'));
            const labelProduk = @json($produkTerlaris->map(fn($item) => $item->produk->nama_produk ?? 'Dihapus'));
            const dataProduk = @json($produkTerlaris->pluck('total_terjual'));
            const labelPembayaran = @json($metodePembayaran->map(fn($item) => $item->pembayaran->nama_pembayaran ?? 'Lainnya'));
            const dataPembayaran = @json($metodePembayaran->pluck('total'));

            new ApexCharts(document.querySelector("#chart-omzet"), {
                series: [{ name: 'Pendapatan (Rp)', data: dataOmzet }],
                chart: { type: 'area', height: 280, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                colors: ['#2563EB'],
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.02, stops: [0, 100] } },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2.5 },
                xaxis: { categories: labelTanggal, tooltip: { enabled: false }, axisBorder: { show: false }, axisTicks: { show: false } },
                yaxis: { labels: { formatter: (value) => "Rp " + (value / 1000000).toFixed(1) + " Jt", style: { colors: ['#9CA3AF'], fontSize: '11px' } } },
                grid: { borderColor: '#F3F4F6', strokeDashArray: 4 }
            }).render();

            new ApexCharts(document.querySelector("#chart-produk"), {
                series: [{ name: 'Terjual (Pcs)', data: dataProduk }],
                chart: { type: 'bar', height: 280, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                plotOptions: { bar: { horizontal: true, borderRadius: 5, barHeight: '45%' } },
                colors: ['#2563EB'],
                dataLabels: { enabled: true, style: { colors: ['#fff'], fontSize: '11px' } },
                xaxis: { categories: labelProduk, axisBorder: { show: false }, axisTicks: { show: false } },
                grid: { borderColor: '#F3F4F6', strokeDashArray: 4 }
            }).render();

            new ApexCharts(document.querySelector("#chart-pembayaran"), {
                series: dataPembayaran,
                labels: labelPembayaran,
                chart: { type: 'donut', height: 280, fontFamily: 'Inter, sans-serif' },
                colors: ['#2563EB', '#10B981', '#F59E0B', '#6366F1'],
                plotOptions: { pie: { donut: { size: '68%', labels: { show: true, total: { show: true, label: 'Total', fontSize: '12px' } } } } },
                dataLabels: { enabled: false },
                legend: { position: 'bottom', fontSize: '12px' }
            }).render();
        });

        // ================= LOGIKA ASISTEN AI =================
        const chatBox = document.getElementById('chat-box');
        const aiInput = document.getElementById('ai-input');
        const btnSendAi = document.getElementById('btn-send-ai');
        const quickPrompts = document.querySelectorAll('.quick-prompt');

        function appendMessage(sender, text) {
            const msgDiv = document.createElement('div');
            msgDiv.className = sender === 'user'
                ? 'bg-blue-600 text-white p-3 rounded-l-2xl rounded-br-2xl max-w-[85%] self-end text-sm shadow-sm leading-relaxed'
                : 'bg-white border border-gray-100 text-gray-800 p-3 rounded-r-2xl rounded-bl-2xl max-w-[85%] self-start text-sm shadow-sm leading-relaxed';
            msgDiv.innerHTML = text.replace(/\n/g, '<br>');
            chatBox.appendChild(msgDiv);
            chatBox.scrollTop = chatBox.scrollHeight;
            return msgDiv;
        }

        async function sendQuestion(question) {
            if (!question.trim()) return;
            appendMessage('user', question);
            aiInput.value = '';
            aiInput.disabled = true;
            btnSendAi.disabled = true;
            const typingMsg = appendMessage('ai', 'Sedang menganalisis data UD Rizki Jaya... ⏳');
            try {
                const response = await fetch("{{ route('admin.tanya.ai') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    body: JSON.stringify({ pertanyaan: question })
                });
                const data = await response.json();
                typingMsg.innerHTML = data.jawaban.replace(/\n/g, '<br>').replace(/\*\*(.*?)\*\*/g, '<b>$1</b>');
            } catch (error) {
                typingMsg.innerHTML = "Maaf, terjadi kesalahan jaringan atau API Key belum diatur.";
            } finally {
                aiInput.disabled = false;
                btnSendAi.disabled = false;
                aiInput.focus();
            }
        }

        btnSendAi.addEventListener('click', () => sendQuestion(aiInput.value));
        aiInput.addEventListener('keypress', function (e) { if (e.key === 'Enter') sendQuestion(aiInput.value); });
        quickPrompts.forEach(button => { button.addEventListener('click', function () { sendQuestion(this.innerText); }); });
    </script>
@endsection