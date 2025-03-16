@extends('layouts.app')

@section('title', 'Penjualan / Kasir')

@section('content')
    <!-- Notifikasi -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Bagian Kiri: Scan Produk dan Tabel -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Scan Produk</h5>
                    <form action="{{ route('transactions.add-to-cart') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="barcode" class="form-control" placeholder="Scan barcode produk disini" autofocus>
                            <button type="submit" class="btn btn-primary">Scan</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Keranjang -->
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Barcode</th>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cart as $item)
                                <tr>
                                    <td>{{ $item['barcode'] }}</td>
                                    <td>{{ $item['name'] }}</td>
                                    <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td>Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Keranjang kosong.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Subtotal dan Total -->
                    <div class="text-end">
                        <p><strong>Subtotal:</strong> Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                        <p><strong>PPN (11%):</strong> Rp {{ number_format($ppn, 0, ',', '.') }}</p>
                        <p><strong>TOTAL:</strong> Rp {{ number_format($total, 0, ',', '.') }}</p>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-between">
                        <form action="{{ route('transactions.cancel') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Batal</button>
                        </form>
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#manualAddModal">Tambah Manual</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian Kanan: Pembayaran -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pembayaran</h5>
                    <form action="{{ route('transactions.checkout') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Info Pelanggan:</label>
                            <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="Nama Pelanggan">
                        </div>
                        <div class="mb-3">
                            <input type="text" name="customer_phone" id="customer_phone" class="form-control" placeholder="No. Telepon (optional)">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran:</label>
                            <div>
                                <input type="radio" name="payment_method" id="tunai" value="tunai" checked>
                                <label for="tunai">Tunai</label>
                                <input type="radio" name="payment_method" id="debit_kredit" value="debit_kredit">
                                <label for="debit_kredit">Debit/Kredit</label>
                                <input type="radio" name="payment_method" id="qris" value="qris">
                                <label for="qris">QRIS</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="amount_paid" class="form-label">Jumlah Diterima:</label>
                            <input type="number" name="amount_paid" id="amount_paid" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kembalian:</label>
                            <input type="text" class="form-control" value="Rp {{ number_format(max(0, ($request->amount_paid ?? 0) - $total), 0, ',', '.') }}" readonly>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Bayar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Tambah Manual -->
    <div class="modal fade" id="manualAddModal" tabindex="-1" aria-labelledby="manualAddModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="manualAddModalLabel">Tambah Produk Manual</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('transactions.manual-add') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="shoe_id" class="form-label">Pilih Produk:</label>
                            <select name="shoe_id" id="shoe_id" class="form-select" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach(\App\Models\Shoe::all() as $shoe)
                                    <option value="{{ $shoe->id }}">{{ $shoe->name }} ({{ $shoe->barcode }})</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection