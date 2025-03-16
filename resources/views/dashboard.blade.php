@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Statistik -->
    <div class="row">
        <div class="col-md-4">
            <div class="stat-card">
                <h5>Total Penjualan Hari Ini</h5>
                <h3>Rp {{ number_format($todaySales, 0, ',', '.') }}</h3>
                <p class="{{ $salesChange >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ $salesChange >= 0 ? '▲' : '▼' }} {{ number_format(abs($salesChange), 0) }}% dari kemarin
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <h5>Pengunjung Hari Ini</h5>
                <h3>{{ $todayVisitors }}</h3>
                <p class="{{ $visitorsChange >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ $visitorsChange >= 0 ? '▲' : '▼' }} {{ number_format(abs($visitorsChange), 0) }}% dari kemarin
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <h5>Total Stok</h5>
                <h3>{{ $totalStock }}</h3>
                <p class="{{ $stockChange >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ $stockChange >= 0 ? '▲' : '▼' }} {{ number_format(abs($stockChange), 0) }}% dari kemarin
                </p>
            </div>
        </div>
    </div>

    <!-- Transaksi Terbaru -->
    <div class="card mt-4">
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
                                <span class="badge {{ $transaction['status'] == 'Selesai' ? 'bg-success' : ($transaction['status'] == 'Proses' ? 'bg-warning' : 'bg-danger') }} text-white">
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

@section('styles')
    <style>
        .stat-card {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background: #fff;
            margin-bottom: 20px;
        }
        .text-success { color: #28a745 !important; }
        .text-danger { color: #dc3545 !important; }
    </style>
@endsection