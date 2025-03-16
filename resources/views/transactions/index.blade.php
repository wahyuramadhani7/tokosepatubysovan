@extends('layouts.app')

@section('title', 'Penjualan')

@section('content')
    <div class="row">
        <!-- Scan Produk -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5>Scan Produk</h5>
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <form action="{{ route('transactions.add-to-cart') }}" method="POST">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="text" name="barcode" class="form-control" placeholder="Scan barcode produk disini" required>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Scan</button>
                            </div>
                        </div>
                    </form>

                    <!-- Tabel Keranjang -->
                    <table class="table table-bordered">
                        <thead>
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

                    <!-- Total -->
                    <div class="text-right">
                        <h5>TOTAL: Rp {{ number_format($total, 0, ',', '.') }}</h5>
                    </div>

                    <!-- Tombol -->
                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('transactions.cancel') }}" class="btn btn-danger">Batal</a>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#manualAddModal">Tambah Manual</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pembayaran -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Pembayaran</h5>
                    <form action="{{ route('transactions.checkout') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Info Pelanggan:</label>
                            <input type="text" name="customer_name" class="form-control mb-2" placeholder="Nama Pelanggan">
                            <input type="text" name="customer_phone" class="form-control" placeholder="No. Telepon (optional)">
                        </div>
                        <div class="form-group">
                            <label>Metode Pembayaran:</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="payment_method" value="tunai" class="form-check-input" required>
                                    <label class="form-check-label">Tunai</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="payment_method" value="debit_kredit" class="form-check-input">
                                    <label class="form-check-label">Debit/Kredit</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="payment_method" value="qris" class="form-check-input">
                                    <label class="form-check-label">QRIS</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Jumlah Diterima:</label>
                            <input type="number" name="amount_paid" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Kembalian:</label>
                            <input type="text" class="form-control" value="Rp {{ number_format(max(0, ($total > 0 ? (request()->input('amount_paid', 0) - $total) : 0)), 0, ',', '.') }}" readonly>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Bayar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Manual -->
    <div class="modal fade" id="manualAddModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Produk Manual</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('transactions.manual-add') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Pilih Produk</label>
                            <select name="shoe_id" class="form-control" required>
                                <option value="">Pilih Produk</option>
                                @foreach(\App\Models\Shoe::all() as $shoe)
                                    <option value="{{ $shoe->id }}">{{ $shoe->name }} ({{ $shoe->size }}) - Rp {{ number_format($shoe->price, 0, ',', '.') }}</option>
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

@section('scripts')
    <script>
        // Script untuk menghitung kembalian secara real-time
        document.querySelector('input[name="amount_paid"]').addEventListener('input', function() {
            const total = {{ $total }};
            const amountPaid = parseInt(this.value) || 0;
            const change = Math.max(0, amountPaid - total);
            document.querySelector('input[readonly]').value = 'Rp ' + change.toLocaleString('id-ID');
        });
    </script>
@endsection