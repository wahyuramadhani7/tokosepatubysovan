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
            background-image: url('/images/bckgr2.png');
            background-size: cover;
            background-position: top center;
            background-repeat: repeat-y;
            background-attachment: fixed;
            color: #ffffff;
            margin: 0;
            padding: 0;
        }
        
        .navbar {
            background-color: #262329; /* Updated to the requested color */
            padding: 10px 20px;
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .navbar-brand {
            color: #ffffff !important;
            font-size: 1.25rem;
            font-weight: bold;
            margin-right: 20px;
        }
        
        .navbar-brand img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
            vertical-align: middle;
        }
        
        .navbar-nav .nav-link {
            color: #ffffff !important;
            margin-left: 15px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .navbar-nav .nav-link.active {
            color: #ff6f61 !important;
        }
        
        .navbar-nav .nav-link:hover {
            color: #ff6f61 !important;
        }
        
        .container {
            padding-top: 0;
        }
        
        /* Responsive styles */
        @media (max-width: 991.98px) {
            .navbar-brand {
                font-size: 1.1rem;
                margin-right: 0;
            }
            
            .navbar-nav .nav-link {
                margin-left: 0;
                padding: 10px 0;
            }
            
            .navbar-collapse {
                background-color: #262329; /* Updated to match the navbar color */
                padding: 10px;
                margin-top: 10px;
                border-radius: 5px;
            }
        }
        
        @media (max-width: 767.98px) {
            .navbar-brand {
                font-size: 1rem;
            }
            
            .navbar-brand img {
                width: 25px;
                height: 25px;
            }
            
            .container {
                padding-left: 15px;
                padding-right: 15px;
            }
        }
        
        @media (max-width: 575.98px) {
            .navbar-brand {
                max-width: 200px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
        }
        
        /* Custom navbar toggler icon for better visibility on dark background */
        .navbar-toggler {
            border-color: rgba(255,255,255,0.5);
            background-color: rgba(255,255,255,0.1);
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
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
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('shoes*') ? 'active' : '' }}" href="{{ route('shoes.index') }}">Inventory</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('transactions*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">Kasir</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Laporan</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>