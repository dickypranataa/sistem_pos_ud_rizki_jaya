@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Analitik Penjualan</h2>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Tren Pendapatan (7 Hari Terakhir)</h3>
        
        <div id="grafikPendapatan"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var options = {
            chart: {
                type: 'area', // Bisa diganti 'bar' atau 'line'
                height: 350,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            series: [{
                name: 'Pendapatan (Rp)',
                // Mengambil data angka dari PHP
                data: @json($totalChart) 
            }],
            xaxis: {
                // Mengambil data tanggal dari PHP
                categories: @json($tanggalChart), 
            },
            colors: ['#4F46E5'], // Warna Indigo khas Tailwind
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.2,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return "Rp " + value.toLocaleString('id-ID');
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#grafikPendapatan"), options);
        chart.render();
    });
</script>
@endsection