<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShoeShop - @yield('title')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js (untuk grafik) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #007bff; /* Sesuaikan dengan header */
            padding: 10px 20px;
            border-bottom: 2px solid #0056b3; /* Garis biru lebih gelap untuk kontras */
        }
        .navbar-brand img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }
        .navbar-nav .nav-link {
            color: #fff !important;
            margin-left: 15px;
            font-weight: 500;
        }
        .navbar-nav .nav-link.active {
            color: #fff;
            background-color: #0056b3; /* Highlight active link */
            border-radius: 5px;
        }
        .header-card {
            background-color: #007bff;
            color: #fff;
            border-radius: 0;
            margin-bottom: 20px;
            padding: 20px;
            border-bottom: 2px solid #0056b3; /* Konsisten dengan navbar */
        }
        .stat-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .stat-card h5 {
            font-size: 1.2rem;
            color: #6c757d;
        }
        .stat-card h3 {
            font-size: 1.8rem;
            font-weight: bold;
            margin: 10px 0;
        }
        .stat-card p {
            font-size: 0.9rem;
            color: #28a745;
        }
        .stat-card p.down {
            color: #dc3545;
        }
        .content-section {
            margin-top: 20px;
            margin-bottom: 40px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .badge-success {
            background-color: #28a745;
        }
        .badge-warning {
            background-color: #ffc107;
        }
        .badge-danger {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="https://via.placeholder.com/30" alt="Logo" class="rounded-circle me-2">
                SHOESHOP
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('shoes*') ? 'active' : '' }}" href="{{ route('shoes.index') }}">Inventory</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('transactions*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">Penjualan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Pengunjung</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Laporan</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div class="container">
        <div class="header-card">
            <h4>Selamat Datang, Admin</h4>
            <p>Ringkasan aktivitas toko sepatu hari ini, {{ now()->format('l, d F Y') }}</p>
        </div>
    </div>

    <!-- Konten Utama -->
    <div class="container content-section">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>