<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Baru</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .section-title {
            background-color: #f28c38;
            color: white;
            padding: 5px 10px;
            border-radius: 5px 5px 0 0;
            display: inline-block;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .card-body {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Tambah Produk Baru</h1>

        <!-- Form -->
        <form action="{{ route('shoes.store') }}" method="POST" class="p-4">
            @csrf

            <!-- Informasi Produk -->
            <div class="card mb-4 shadow">
                <div class="section-title">
                    <span class="me-2">📦</span> Informasi Produk
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Produk</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="purchase_price" class="form-label">Harga Beli</label>
                            <input type="number" name="purchase_price" id="purchase_price" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Harga Jual</label>
                            <input type="number" name="price" id="price" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="brand" class="form-label">Merek</label>
                            <input type="text" name="brand" id="brand" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Kategori</label>
                            <input type="text" name="category" id="category" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sku" class="form-label">Kode SKU</label>
                            <input type="text" name="sku" id="sku" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Size -->
            <div class="card mb-4 shadow">
                <div class="section-title">
                    <span class="me-2">📏</span> Size
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="size" class="form-label">Ukuran</label>
                            <input type="text" name="size" id="size" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="color" class="form-label">Warna</label>
                            <input type="text" name="color" id="color" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="stock" class="form-label">Stok Awal</label>
                            <input type="number" name="stock" id="stock" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="min_stock" class="form-label">Minimum Stok</label>
                            <input type="number" name="min_stock" id="min_stock" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="supplier" class="form-label">Supplier</label>
                            <input type="text" name="supplier" id="supplier" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deskripsi Produk -->
            <div class="card mb-4 shadow">
                <div class="section-title">
                    Deskripsi Produk
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <textarea name="description" id="description" class="form-control" rows="5" required></textarea>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('shoes.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>