@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Statistik -->
    <div class="row">
        <div class="col-md-4">
            <div class="stat-card">
                <h5>Total Penjualan Hari Ini</h5>
                <h3>Rp {{ number_format($todaySales, 0, ',', '.') }}</h3>
                <p class="{{ $salesChange >= 0 ? '' : 'down' }}">
                    {{ $salesChange >= 0 ? '▲' : '▼' }} {{ number_format(abs($salesChange), 0) }}% dari kemarin
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <h5>Pengunjung Hari Ini</h5>
                <h3>{{ $todayVisitors }}</h3>
                <p class="{{ $visitorsChange >= 0 ? '' : 'down' }}">
                    {{ $visitorsChange >= 0 ? '▲' : '▼' }} {{ number_format(abs($visitorsChange), 0) }}% dari kemarin
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <h5>Total Stok</h5>
                <h3>{{ $totalStock }}</h3>
                <p class="{{ $stockChange >= 0 ? '' : 'down' }}">
                    {{ $stockChange >= 0 ? '▲' : '▼' }} {{ number_format(abs($stockChange), 0) }}% dari kemarin
                </p>
            </div>
        </div>
    </div>

    <!-- Grafik -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Grafik Penjualan Mingguan</h5>
                    <canvas id="weeklySalesChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Produk Terlaris</h5>
                    <canvas id="topProductsChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaksi Terbaru -->
    <div class="card">
        <div class="card-body">
            <h5>Transaksi Terbaru</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Pelanggan</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $transaction)
                        <tr>
                            <td>{{ $transaction['id'] }}</td>
                            <td>{{ $transaction['customer'] }}</td>
                            <td>{{ $transaction['product'] }}</td>
                            <td>{{ $transaction['quantity'] }}</td>
                            <td>Rp {{ number_format($transaction['total'], 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $transaction['status'] == 'Selesai' ? 'badge-success' : ($transaction['status'] == 'Proses' ? 'badge-warning' : 'badge-danger') }}">
                                    {{ $transaction['status'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada transaksi terbaru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <a href="#" class="float-end">Lihat Semua ></a>
        </div>
    </div>
@endsection

<!-- Script untuk Grafik -->
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Debugging: Tampilkan data di console
            console.log('Labels:', @json($labels));
            console.log('Weekly Sales:', @json($weeklySales));
            console.log('Weekly Target:', @json($weeklyTarget));
            console.log('Top Product Labels:', @json($topProductLabels));
            console.log('Top Product Data:', @json($topProductData));

            // Pastikan elemen canvas ada
            const weeklySalesCanvas = document.getElementById('weeklySalesChart');
            const topProductsCanvas = document.getElementById('topProductsChart');
            if (!weeklySalesCanvas || !topProductsCanvas) {
                console.error('Canvas element not found');
                return;
            }

            // Grafik Penjualan Mingguan
            const weeklySalesChart = new Chart(weeklySalesCanvas, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Penjualan',
                        data: @json($weeklySales),
                        borderColor: 'green',
                        fill: false
                    }, {
                        label: 'Target',
                        data: @json($weeklyTarget),
                        borderColor: 'blue',
                        borderDash: [5, 5],
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });

            // Pie Chart Produk Terlaris
            const topProductsChart = new Chart(topProductsCanvas, {
                type: 'pie',
                data: {
                    labels: @json($topProductLabels),
                    datasets: [{
                        data: @json($topProductData),
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
                    }]
                }
            });

            // Debugging: Periksa apakah chart berhasil dibuat
            console.log('Weekly Sales Chart:', weeklySalesChart);
            console.log('Top Products Chart:', topProductsChart);
        });
    </script>
@endsection