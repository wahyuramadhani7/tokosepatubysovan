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
                        <a href="#" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#manualAddModal">Tambah Manual</a>
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
    <div class="modal fade" id="manualAddModal" tabindex="-1" aria-labelledby="manualAddModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="manualAddModalLabel">Tambah Produk Manual</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('transactions.add-to-cart') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="shoe_select">Pilih Sepatu dari Inventory</label>
                            <select class="form-control" id="shoe_select" name="barcode" required>
                                <option value="">-- Pilih Sepatu --</option>
                                @forelse($shoes as $shoe)
                                    <option value="{{ $shoe->barcode }}" 
                                            data-name="{{ $shoe->name }}" 
                                            data-price="{{ $shoe->price }}" 
                                            data-stock="{{ $shoe->stock }}"
                                            {{ $shoe->stock <= 0 ? 'disabled' : '' }}>
                                        {{ $shoe->barcode }} - {{ $shoe->name }} (Stok: {{ $shoe->stock }}, Harga: Rp {{ number_format($shoe->price, 0, ',', '.') }})
                                    </option>
                                @empty
                                    <option value="" disabled>Tidak ada sepatu di inventory</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Detail Sepatu:</label>
                            <p id="shoe_details" class="text-muted">Pilih sepatu untuk melihat detail.</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Tambah ke Keranjang</button>
                    </div>
                </form>
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
            const change = Math.max(0, amountPaidAssociated Press - total);
            document.querySelector('input[readonly]').value = 'Rp ' + change.toLocaleString('id-ID');
        });

        // Script untuk menampilkan detail sepatu saat dipilih
        document.getElementById('shoe_select').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const name = selectedOption.getAttribute('data-name');
            const price = selectedOption.getAttribute('data-price');
            const stock = selectedOption.getAttribute('data-stock');

            if (name && price && stock) {
                document.getElementById('shoe_details').innerHTML = 
                    `Nama: ${name}<br>Harga: Rp ${parseInt(price).toLocaleString('id-ID')}<br>Stok: ${stock}`;
            } else {
                document.getElementById('shoe_details').innerHTML = 'Pilih sepatu untuk melihat detail.';
            }
        });
    </script>
@endsection