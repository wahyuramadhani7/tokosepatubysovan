@extends('layouts.app')

@section('title', 'Manajemen Inventory')

@section('content')
    <!-- Notifikasi Sukses -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filter dan Pencarian -->
    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Cari produk..." style="border-radius: 5px;">
        </div>
        <div class="col-md-6 text-end">
            <select class="form-select d-inline-block w-auto me-2">
                <option value="">Merek</option>
                <option value="nike">Nike</option>
                <option value="adidas">Adidas</option>
                <option value="puma">Puma</option>
                <option value="converse">Converse</option>
            </select>
            <select class="form-select d-inline-block w-auto me-2">
                <option value="">Kategori</option>
                <option value="sepatu-lari">Sepatu Lari</option>
                <option value="sepatu-casual">Sepatu Casual</option>
            </select>
            <select class="form-select d-inline-block w-auto">
                <option value="">Status</option>
                <option value="menipis">Menipis</option>
                <option value="berlebih">Berlebih</option>
            </select>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <h5>Total Produk</h5>
                <h3>{{ $shoes->count() }}</h3>
                <p>25 produk baru bulan ini</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h5>Nilai Inventory</h5>
                <h3>Rp {{ number_format($shoes->sum('price'), 0, ',', '.') }}</h3>
                <p>↑ 8.5% dari bulan lalu</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h5>Stok Menipis</h5>
                <h3>{{ $shoes->where('stock', '<=', 5)->count() }}</h3>
                <p>Perlu pemesanan kembali</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h5>Stok Berlebih</h5>
                <h3>{{ $shoes->where('stock', '>=', 10)->count() }}</h3>
                <p>Pertimbangkan diskon</p>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('shoes.create') }}" class="btn btn-success me-2">Tambah Produk</a>
            <button class="btn btn-primary me-2">Export Data</button>
            <button class="btn btn-warning me-2">Update Harga</button>
            <button class="btn btn-info me-2">Analisis Stok</button>
            <button class="btn btn-secondary">Buka Riwayat</button>
        </div>
    </div>

    <!-- Tabel Stok -->
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Barcode</th>
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
                            <td>
                                {{ $shoe->barcode ?? 'Tidak ada barcode' }}
                                <br>
                                {!! $shoe->qrCode !!} <!-- Tampilkan QR Code -->
                            </td>
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
                                <a href="{{ route('shoes.print-barcode', $shoe->id) }}" class="btn btn-sm btn-info me-1">Cetak QR</a>
                                <a href="{{ route('shoes.edit', $shoe->id) }}" class="btn btn-sm btn-primary me-1">Edit</a>
                                <form action="{{ route('shoes.destroy', $shoe->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data sepatu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <!-- Pagination -->
            <div class="d-flex justify-content-between">
                <span>{{ $shoes->links() }}</span>
                <span>1 2 3 ... 8</span>
            </div>
        </div>
    </div>
@endsection