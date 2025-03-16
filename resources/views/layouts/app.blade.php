<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Sepatu By Sovan - @yield('title')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js (untuk grafik jika diperlukan di halaman lain) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-image: url('/images/bckgr2.png'); /* Ganti dengan path gambar garis */
            background-size: cover;
            background-position: top center;
            background-repeat: repeat-y; /* Ulangi secara vertikal */
            background-attachment: fixed; /* Agar background tetap */
            color: #ffffff; /* Warna teks default putih untuk kontras */
            margin: 0; /* Hapus margin bawaan body */
            padding: 0; /* Hapus padding bawaan body */
        }
        .navbar {
            background-color: #1E1E1E; /* Warna abu-abu gelap */
            padding: 10px 20px; /* Padding minimal */
            border-bottom: none; /* Hapus garis oranye */
            margin-bottom: 0; /* Hapus margin bawah navbar */
        }
        .navbar-brand {
            color: #ffffff !important; /* Teks putih */
            font-size: 1.25rem;
            font-weight: bold;
            margin-right: 20px; /* Jarak dari menu */
        }
        .navbar-brand img {
            width: 30px; /* Ukuran ikon/logo */
            height: 30px;
            margin-right: 10px;
            vertical-align: middle; /* Align dengan teks */
        }
        .navbar-nav .nav-link {
            color: #ffffff !important; /* Teks menu putih */
            margin-left: 15px; /* Jarak antar menu */
            font-weight: 500;
            text-transform: uppercase; /* Huruf kapital seperti di gambar */
        }
        .navbar-nav .nav-link.active {
            color: #ff6f61 !important; /* Warna oranye untuk link aktif */
        }
        .navbar-nav .nav-link:hover {
            color: #ff6f61 !important; /* Efek hover oranye */
        }
        .container {
            padding-top: 0; /* Hapus padding atas konten */
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="/images/logotokosepatu.jpg" alt="Logo" class="rounded-circle">
                TOKO SEPATU BY SOVAN
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
                        <a class="nav-link {{ Request::is('transactions*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">Transaksi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Laporan</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="container">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>