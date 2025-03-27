@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Background Banner -->
    <div class="shoe-background d-flex justify-content-center align-items-center">
        <div class="brand-container">
            <h3 class="brand-text">@SEPATUBYSOVAN</h3>
        </div>
    </div>

    <div class="container mt-4">
        <h2 class="text-center mb-4" style="color: black;">LAPORAN HARIAN</h2>

        <!-- Stats Cards -->
        <div class="row mb-4">
            @php 
                $statCards = [
                    ['icon' => '&#128095;', 'title' => 'Total Produk', 'value' => $totalStock],
                    ['icon' => '&#128101;', 'title' => 'Pengunjung Harian', 'value' => $todayVisitors],
                    ['icon' => '&#128230;', 'title' => 'Total Stok', 'value' => $totalStock]
                ];
            @endphp
            
            @foreach($statCards as $card)
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <span class="icon-stat">{!! $card['icon'] !!}</span>
                        </div>
                        <h5>{{ $card['title'] }}</h5>
                        <h3>{{ $card['value'] }}</h3>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Rest of your code remains unchanged -->
        <!-- Charts -->
        <div class="row mb-4">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card bg-dark text-white">
                    <div class="card-body">
                        <h5 class="text-white">Produk Terlaris</h5>
                        <div class="chart-container" style="position: relative; height:250px;">
                            <canvas id="topProductsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-6 mb-4">
                <div class="card bg-dark text-white">
                    <div class="card-body">
                        <h5 class="text-white">Grafik Pengunjung Mingguan</h5>
                        <p class="text-muted">Laporan Pengunjung Selama Seminggu</p>
                        <div class="chart-container" style="position: relative; height:250px;">
                            <canvas id="weeklyVisitorsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card mt-4 bg-dark text-white">
            <div class="card-body">
                <h5 class="text-white">Detail Transaksi</h5>
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                @foreach(['ID Transaksi', 'Pelanggan', 'Produk', 'Jumlah', 'Total', 'Status'] as $header)
                                    <th class="text-white">{{ $header }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $transaction)
                                <tr>
                                    <td class="text-black">{{ $transaction['id'] ?? 'N/A' }}</td>
                                    <td class="text-black">{{ $transaction['customer'] ?? 'N/A' }}</td>
                                    <td class="text-black">{{ $transaction['product'] ?? 'N/A' }}</td>
                                    <td class="text-black">{{ $transaction['quantity'] ?? 'N/A' }}</td>
                                    <td class="text-black">Rp {{ number_format($transaction['total'] ?? 0, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $statusClass = [
                                                'Selesai' => 'bg-success',
                                                'Proses' => 'bg-warning',
                                                'default' => 'bg-danger'
                                            ];
                                            $class = $statusClass[$transaction['status']] ?? $statusClass['default'];
                                        @endphp
                                        <span class="badge {{ $class }}">
                                            {{ $transaction['status'] ?? 'N/A' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-black text-center">Tidak ada transaksi terbaru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <a href="#" class="float-end btn btn-custom-link text-white">Lihat Semua ></a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Your scripts remain unchanged -->
    <script>
        // Chart data
        const chartData = {
            products: {
                labels: ['Nike', 'Adidas', 'Puma'],
                data: [40, 30, 30],
                colors: ['#ff6f61', '#6b7280', '#f4a261']
            },
            visitors: {
                labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                data: [50, 70, 30, 90, 60, 80, 40]
            }
        };

        // Responsive font size function
        const responsiveFont = () => window.innerWidth < 768 ? 10 : 12;
        
        // Initialize charts
        const topProductsChart = new Chart(document.getElementById('topProductsChart'), {
            type: 'pie',
            data: {
                labels: chartData.products.labels,
                datasets: [{
                    data: chartData.products.data,
                    backgroundColor: chartData.products.colors
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: 'white',
                            font: { size: responsiveFont },
                            boxWidth: () => window.innerWidth < 768 ? 10 : 15
                        }
                    }
                }
            }
        });

        const weeklyVisitorsChart = new Chart(document.getElementById('weeklyVisitorsChart'), {
            type: 'line',
            data: {
                labels: chartData.visitors.labels,
                datasets: [{
                    label: 'Pengunjung',
                    data: chartData.visitors.data,
                    borderColor: '#f4a261',
                    backgroundColor: 'rgba(244, 162, 97, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            color: 'white',
                            font: { size: responsiveFont }
                        }
                    },
                    x: {
                        ticks: { 
                            color: 'white',
                            font: { size: responsiveFont }
                        }
                    }
                },
                plugins: { legend: { display: false } }
            }
        });

        // Resize charts on window resize
        window.addEventListener('resize', () => {
            topProductsChart.resize();
            weeklyVisitorsChart.resize();
        });
    </script>
@endsection

@section('styles')
    <style>
        /* Base styles */
        .shoe-background {
            background-image: url('/images/bgbaru.png');
            background-size: cover;
            background-position: center;
            height: 350px;
            width: 100vw;
            margin: 0 -50vw;
            position: relative;
            left: 50%;
        }
        
        /* Updated brand container and text styling */
        .brand-container {
            background-color: #000000;
            padding: 15px 25px;
            border-radius: 10px;
            display: inline-block;
        }
        
        .brand-text {
            color: #FF5722;
            font-size: 2rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0;
        }

        /* Stat cards */
        .stat-card {
            padding: 20px;
            border-radius: 8px;
            background: #f5f5f5;
            text-align: center;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            height: 150px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }
        
        .stat-icon {
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 40px;
            background: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ff5722;
        }
        
        .icon-stat {
            font-size: 20px;
            color: #ff5722;
        }
        
        .stat-card h5 {
            font-size: 1rem;
            margin: 0;
            color: #333;
            font-weight: 600;
        }
        
        .stat-card h3 {
            font-size: 1.5rem;
            margin: 0;
            color: #333;
            font-weight: 700;
        }

        /* Card & Table styles */
        .card {
            background-color: #2d2d2d !important;
            border: none !important;
        }
        
        .table-custom {
            background-color: #ffffff !important;
            border-radius: 8px !important;
            overflow: hidden !important;
            width: 100%;
        }
        
        .table-custom th {
            background-color: #4a4a4a !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            padding: 12px !important;
            text-align: center !important;
        }
        
        .table-custom td {
            padding: 12px !important;
            text-align: center !important;
            border-bottom: 1px solid #d1d1d1 !important;
            color: #000000 !important;
        }
        
        .table-custom tr:nth-child(even) { background-color: #f2f2f2 !important; }
        .table-custom tr:nth-child(odd) { background-color: #ffffff !important; }
        
        .badge {
            display: inline-block !important;
            padding: 6px 12px !important;
            font-weight: 700 !important;
            border-radius: 5px !important;
        }
        
        /* Media queries */
        @media (max-width: 991.98px) {
            .shoe-background { height: 250px; }
            .brand-text { font-size: 1.75rem; }
        }
        
        @media (max-width: 767.98px) {
            .shoe-background { height: 200px; }
            .brand-text { font-size: 1.5rem; }
            .brand-container { padding: 12px 20px; }
            .stat-card { height: 130px; }
            .stat-card h5 { font-size: 0.9rem; }
            .stat-card h3 { font-size: 1.3rem; }
            .table-custom th, .table-custom td { 
                font-size: 0.9rem !important; 
                padding: 8px !important; 
            }
            .badge { 
                font-size: 0.8rem !important; 
                padding: 4px 8px !important; 
            }
        }
        
        @media (max-width: 575.98px) {
            .shoe-background { height: 150px; }
            .brand-text { font-size: 1.25rem; }
            .brand-container { padding: 10px 15px; }
            h2.text-center { font-size: 1.5rem; }
            .card-body { padding: 15px; }
            .card h5 { font-size: 1rem; }
        }
    </style>
@endsection