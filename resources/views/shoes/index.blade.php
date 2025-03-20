@extends('layouts.app')

@section('title', 'Manajemen Inventory')

@section('content')
<style>
    /* Styling untuk header navigasi */
    .navbar {
        background-color: #1a2526;
    }
    .navbar a {
        color: white !important;
        font-weight: 500;
    }

    /* Styling untuk judul utama */
    h2 {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin-bottom: 20px;
        margin-top: 40px;
    }

    /* Styling untuk judul bagian */
    .section-title {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
        position: relative;
        display: block;
        text-align: center;
    }
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 50%;
        transform: translateX(-50%);
        width: 50%;
        height: 2px;
        background-color: #007bff;
    }

    /* Styling untuk kartu statistik */
    .stat-card {
        background-color: #fff5e6;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
    }
    .stat-card img {
        width: 40px;
        height: 40px;
        margin-bottom: 10px;
    }
    .stat-card h5 {
        font-size: 14px;
        color: #666;
        margin: 0;
        text-transform: uppercase;
    }
    .stat-card h3 {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin: 5px 0 0;
    }

    /* Styling untuk filter dan pencarian */
    .search-bar {
        position: relative;
    }
    .search-bar input {
        padding-left: 35px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f5f5f5;
    }
    .search-bar::before {
        content: url('https://img.icons8.com/ios-filled/20/000000/search.png');
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
    }

    /* Styling untuk tombol aksi */
    .btn-tambah {
        background-color: #ffffff;
        border: 1px solid #ccc;
        color: #333;
        border-radius: 5px;
    }
    .btn-tambah:hover {
        background-color: #f0f0f0;
    }
    .btn-history {
        position: relative;
        padding-right: 30px;
        background-color: #d3d3d3;
        border: 1px solid #ccc;
        color: #333;
        border-radius: 5px;
    }
    .btn-history:hover {
        background-color: #c0c0c0;
    }
    .btn-history::after {
        content: url('https://img.icons8.com/ios-filled/16/000000/refresh.png');
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
    }
    .btn-orange {
        background-color: #f28c38;
        border-color: #f28c38;
        color: white;
    }
    .btn-orange:hover {
        background-color: #e07b30;
        border-color: #e07b30;
    }

    /* Styling untuk tabel */
    .table thead th {
        background-color: #f28c38;
        color: white;
        border: none;
    }
    .table tbody tr:nth-child(odd) {
        background-color: #f8f9fa;
    }
    .table tbody tr:nth-child(even) {
        background-color: #ffffff;
    }
    .table-bordered {
        border: 1px solid #e0e0e0;
    }
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #e0e0e0;
    }

    /* Styling untuk QR Code */
    .qr-code img {
        max-width: 100px;
        height: auto;
    }
</style>

<!-- Notifikasi Sukses -->
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Notifikasi Error -->
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<h2>MANAJEMEN INVENTORY</h2>

<!-- Statistik -->
<div class="row mb-4" style="background-color: #1a2526; padding: 20px; border-radius: 8px;">
    <div class="col-12">
        <div class="section-title" style="color: white;">INVENTORY INFORMATION</div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <img src="https://img.icons8.com/ios-filled/40/f28c38/sneaker.png" alt="Total Produk">
            <h5>Total Produk</h5>
            <h3>{{ $shoes->count() }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="border: 2px solid #007bff;">
            <img src="https://img.icons8.com/ios-filled/40/f28c38/box.png" alt="Stok Menipis">
            <h5>Stok Menipis</h5>
            <h3>{{ $shoes->where('stock', '<=', 5)->count() }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <img src="https://img.icons8.com/ios-filled/40/f28c38/calculator.png" alt="Total Stok">
            <h5>Total Stok</h5>
            <h3>{{ $shoes->sum('stock') }}</h3>
        </div>
    </div>
</div>

<!-- Filter, Tombol Aksi, dan Tabel Stok -->
<div class="row" style="background-color: #1a2526; padding: 20px; border-radius: 8px;">
    <div class="col-12">
        <div class="row mb-3 align-items-center">
            <div class="col-md-6">
                <div class="search-bar">
                    <input type="text" class="form-control" placeholder="Cari produk...">
                </div>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('shoes.create') }}" class="btn btn-tambah me-2">
                    <img src="https://img.icons8.com/ios-filled/16/000000/plus-math.png" alt="Tambah Icon" class="me-1"> Tambah
                </a>
                <button class="btn btn-history">Riwayat</button>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Barcode</th>
                            <th>QR Code</th> <!-- Kolom baru untuk QR Code -->
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Ukuran</th>
                            <th>Stok</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shoes as $shoe)
                            <tr>
                                <td>{{ $shoe->barcode ?? 'BELUM TERSET' }}</td>
                                <td class="qr-code">{!! $shoe->qrCode !!}</td> <!-- Tampilkan QR Code -->
                                <td>{{ $shoe->name }}</td>
                                <td>Sepatu {{ rand(0, 1) ? 'Lari' : 'Casual' }}</td>
                                <td>{{ $shoe->size }}</td>
                                <td>
                                    @if($shoe->stock <= 5)
                                        <span class="badge badge-danger">{{ $shoe->stock }}</span>
                                    @elseif($shoe->stock >= 10)
                                        <span class="badge badge-success">{{ $shoe->stock }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ $shoe->stock }}</span>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($shoe->price * 0.7, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($shoe->price, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('shoes.edit', $shoe->id) }}" class="btn btn-sm btn-orange me-1">Edit</a>
                                    <form action="{{ route('shoes.destroy', $shoe->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Belum ada data sepatu.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <!-- Pagination -->
                <div class="d-flex justify-content-between">
                    <span>{{ $shoes->links() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection