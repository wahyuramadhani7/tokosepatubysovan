@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Background Gambar Sepatu (di bawah navbar) -->
    <div class="shoe-background d-flex justify-content-center align-items-center">
        <h3 class="shoe-text">@SEPATUBYSOVAN</h3>
    </div>

    <!-- Konten Utama -->
    <div class="container mt-4">
        <!-- Judul Laporan Harian -->
        <h2 class="text-center mb-4" style="color: black;">LAPORAN HARIAN</h2>

        <!-- Statistik -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <span class="icon-shoe">&#128095;</span> <!-- Unicode for a shoe -->
                    </div>
                    <h5>Total Produk</h5>
                    <h3>{{ $totalStock }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <span class="icon-visitor">&#128101;</span> <!-- Unicode for a group of people -->
                    </div>
                    <h5>Pengunjung Harian</h5>
                    <h3>{{ $todayVisitors }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <span class="icon-stock">&#128230;</span> <!-- Unicode for a package/stock symbol -->
                    </div>
                    <h5>Total Stok</h5>
                    <h3>{{ $totalStock }}</h3>
                </div>
            </div>
        </div>

        <!-- Grafik -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-dark text-white">
                    <div class="card-body">
                        <h5 class="text-white">Produk Terlaris</h5>
                        <canvas id="topProductsChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card bg-dark text-white">
                    <div class="card-body">
                        <h5 class="text-white">Grafik Pengunjung Mingguan</h5>
                        <p class="text-muted">Laporan Pengunjung Selama Seminggu</p>
                        <canvas id="weeklyVisitorsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaksi Terbaru -->
        <div class="card mt-4 bg-dark text-white">
            <div class="card-body">
                <h5 class="text-white">Detail Transaksi</h5>
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th class="text-white">ID Transaksi</th>
                            <th class="text-white">Pelanggan</th>
                            <th class="text-white">Produk</th>
                            <th class="text-white">Jumlah</th>
                            <th class="text-white">Total</th>
                            <th class="text-white">Status</th>
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
                                    <span class="badge {{ $transaction['status'] == 'Selesai' ? 'bg-success' : ($transaction['status'] == 'Proses' ? 'bg-warning' : 'bg-danger') }}">
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
                <a href="#" class="float-end btn btn-custom-link text-white">Lihat Semua ></a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Pie Chart untuk Produk Terlaris
        const topProductsChart = new Chart(document.getElementById('topProductsChart'), {
            type: 'pie',
            data: {
                labels: ['Nike', 'Adidas', 'Puma'],
                datasets: [{
                    data: [40, 30, 30],
                    backgroundColor: ['#ff6f61', '#6b7280', '#f4a261']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: 'white'
                        }
                    }
                }
            }
        });

        // Line Chart untuk Pengunjung Mingguan
        const weeklyVisitorsChart = new Chart(document.getElementById('weeklyVisitorsChart'), {
            type: 'line',
            data: {
                labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Freaky', 'Sabtu', 'Minggu'],
                datasets: [{
                    label: 'Pengunjung',
                    data: [50, 70, 30, 90, 60, 80, 40],
                    borderColor: '#f4a261',
                    backgroundColor: 'rgba(244, 162, 97, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: 'white' }
                    },
                    x: {
                        ticks: { color: 'white' }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endsection

@section('styles')
    <style>
        /* Style untuk background gambar sepatu */
        .shoe-background {
            background-image: url('/images/bgdahsb.png');
            background-size: cover;
            background-position: center;
            height: 350px;
            width: 100vw;
            margin: 0;
            padding: 0;
            position: relative;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
            margin-top: 0;
        }
        .shoe-text {
            color: #ffffff;
            font-size: 2rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            z-index: 1;
        }

        /* Statistik Card */
        .stat-card {
            padding: 20px;
            border-radius: 8px;
            background: #f5f5f5; /* Light gray background to match the image */
            text-align: center;
            border: 1px solid #e0e0e0;
            position: relative;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            height: 150px; /* Fixed height to match the image */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .stat-card .stat-icon {
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 40px;
            background: #ffffff; /* White background for icon circle */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ff5722; /* Orange border to match the image */
        }
        .stat-card .icon-shoe,
        .stat-card .icon-visitor,
        .stat-card .icon-stock {
            font-size: 20px;
            color: #ff5722; /* Orange color to match the image */
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

        /* Card styling */
        .card {
            background-color: #2d2d2d !important;
            border: none !important;
        }
        .card-body {
            padding: 20px;
        }

        /* Custom styles for the transaction table */
        .table-custom {
            background-color: #ffffff !important;
            color: #000000 !important;
            border-radius: 8px !important;
            overflow: hidden !important;
            font-family: 'Roboto', sans-serif !important;
            border-collapse: collapse !important;
        }
        .table-custom th {
            background-color: #4a4a4a !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 1rem !important;
            padding: 12px !important;
            text-align: center !important;
            border-bottom: 2px solid #d1d1d1 !important;
        }
        .table-custom td {
            padding: 12px !important;
            text-align: center !important;
            border-bottom: 1px solid #d1d1d1 !important;
            color: #000000 !important;
            font-weight: 400 !important;
            font-size: 0.95rem !important;
        }
        .table-custom tr:nth-child(even) {
            background-color: #f2f2f2 !important;
        }
        .table-custom tr:nth-child(odd) {
            background-color: #ffffff !important;
        }
        .table-custom .badge {
            display: inline-block !important;
            padding: 6px 12px !important;
            font-size: 0.9rem !important;
            font-weight: 700 !important;
            border-radius: 5px !important;
            font-family: 'Roboto', sans-serif !important;
        }
        .table-custom .bg-success {
            background-color: #28a745 !important;
            color: #ffffff !important;
        }
        .table-custom .bg-warning {
            background-color: #ffc107 !important;
            color: #000000 !important;
        }
        .table-custom .bg-danger {
            background-color: #dc3545 !important;
            color: #ffffff !important;
        }
        .btn-custom-link {
            color: #ff6f61 !important;
            text-decoration: none !important;
            font-weight: 700 !important;
            font-size: 0.95rem !important;
            font-family: 'Roboto', sans-serif !important;
        }
        .btn-custom-link:hover {
            color: #ff8f81 !important;
        }
    </style>
@endsection