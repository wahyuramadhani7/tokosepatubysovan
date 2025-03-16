<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Sepatu</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Tambah Sepatu Baru</h1>

        <!-- Form -->
        <form action="{{ route('shoes.store') }}" method="POST" class="card p-4 shadow">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Sepatu</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="size" class="form-label">Ukuran</label>
                <input type="text" name="size" id="size" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Harga (Rp)</label>
                <input type="number" name="price" id="price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stok</label>
                <input type="number" name="stock" id="stock" class="form-control" required>
            </div>
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