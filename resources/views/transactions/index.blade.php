@extends('layouts.app')

@section('title', 'Penjualan')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark" style="font-size: 1.75rem;">
            <i class="fas fa-shopping-cart me-2 text-primary"></i>Point of Sale
        </h2>
        <div>
            <span class="text-muted small">Tanggal: {{ now()->format('d M Y') }}</span>
        </div>
    </div>

    <div class="row g-4">
        <!-- Scan Produk dan Keranjang -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-qrcode me-2"></i>Scan Produk
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Notifikasi -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Form Scan -->
                    <div class="row mb-4 align-items-center">
                        <div class="col-md-8">
                            <form action="{{ route('transactions.add-to-cart') }}" method="POST" id="qrcode-form">
                                @csrf
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0" style="border-radius: 8px 0 0 8px;">
                                        <i class="fas fa-qrcode text-muted"></i>
                                    </span>
                                    <input type="text" name="barcode" id="qrcode-input" class="form-control" style="border-radius: 0 8px 8px 0;" required autofocus>
                                    <button class="btn btn-primary ms-2" type="submit" style="border-radius: 8px; transition: all 0.3s;">
                                        <i class="fas fa-qrcode me-2"></i>Scan
                                    </button>
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    <i class="fas fa-info-circle me-1"></i>Gunakan tombol "Buka Kamera" untuk scan QR Code
                                </small>
                            </form>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <button class="btn btn-outline-info" id="open-camera-btn" style="border-radius: 8px; transition: all 0.3s;">
                                <i class="fas fa-camera me-2"></i>Buka Kamera
                            </button>
                        </div>
                    </div>

                    <!-- Camera Preview -->
                    <div id="camera-container" class="mb-4 d-none">
                        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                            <div class="card-header bg-info text-white py-2 d-flex justify-content-between align-items-center">
                                <span class="fw-semibold"><i class="fas fa-camera me-2"></i>QR Code Scanner</span>
                                <button class="btn btn-sm btn-outline-light" id="close-camera-btn" style="border-radius: 6px;">
                                    <i class="fas fa-times"></i> Tutup
                                </button>
                            </div>
                            <div class="card-body p-2 text-center position-relative">
                                <video id="camera-preview" autoplay playsinline style="width: 100%; max-height: 300px; border-radius: 8px;"></video>
                                <canvas id="canvas-capture" style="display: none;"></canvas>
                                <div class="scan-region-highlight"></div>
                                <div class="alert alert-info mt-2 mb-0" id="camera-message" style="border-radius: 6px;">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <span id="camera-message-text">Sedang mengaktifkan kamera...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Keranjang Belanja -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-semibold mb-0 text-dark">
                            <i class="fas fa-shopping-basket me-2 text-primary"></i>Keranjang Belanja
                        </h5>
                        <div>
                            <button class="btn btn-outline-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#manualAddModal" style="border-radius: 6px;">
                                <i class="fas fa-plus me-1"></i>Tambah Manual
                            </button>
                            <form action="{{ route('transactions.cancel') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin ingin membatalkan transaksi?')" style="border-radius: 6px;">
                                    <i class="fas fa-trash me-1"></i>Kosongkan
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" style="border-radius: 8px; overflow: hidden;">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3">QR Code</th>
                                    <th class="py-3">Produk</th>
                                    <th class="py-3 text-end">Harga</th>
                                    <th class="py-3 text-center">Qty</th>
                                    <th class="py-3 text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cart as $item)
                                    <tr>
                                        <td><span class="badge bg-secondary text-white">{{ $item['barcode'] }}</span></td>
                                        <td class="fw-medium">{{ $item['name'] }}</td>
                                        <td class="text-end">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary rounded-pill">{{ $item['quantity'] }}</span>
                                        </td>
                                        <td class="text-end fw-bold">Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                            <p class="mb-0">Keranjang belanja masih kosong</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="4" class="text-end fw-bold py-3">TOTAL</td>
                                    <td class="text-end fw-bold text-primary py-3">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pembayaran -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px; border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-credit-card me-2"></i>Pembayaran
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('transactions.checkout') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-medium text-dark">Info Pelanggan</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text bg-light border-0" style="border-radius: 8px 0 0 8px;">
                                    <i class="fas fa-user text-muted"></i>
                                </span>
                                <input type="text" name="customer_name" class="form-control" placeholder="Nama Pelanggan" style="border-radius: 0 8px 8px 0;">
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0" style="border-radius: 8px 0 0 8px;">
                                    <i class="fas fa-phone text-muted"></i>
                                </span>
                                <input type="text" name="customer_phone" class="form-control" placeholder="No. Telepon (opsional)" style="border-radius: 0 8px 8px 0;">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium text-dark">Metode Pembayaran</label>
                            <div class="d-flex flex-wrap gap-2">
                                <div class="form-check payment-method">
                                    <input type="radio" name="payment_method" value="tunai" class="form-check-input" id="payment_cash" required>
                                    <label class="form-check-label payment-label px-3 py-2" for="payment_cash">
                                        <i class="fas fa-money-bill-wave me-2"></i>Tunai
                                    </label>
                                </div>
                                <div class="form-check payment-method">
                                    <input type="radio" name="payment_method" value="debit_kredit" class="form-check-input" id="payment_card">
                                    <label class="form-check-label payment-label px-3 py-2" for="payment_card">
                                        <i class="fas fa-credit-card me-2"></i>Debit/Kredit
                                    </label>
                                </div>
                                <div class="form-check payment-method">
                                    <input type="radio" name="payment_method" value="qris" class="form-check-input" id="payment_qris">
                                    <label class="form-check-label payment-label px-3 py-2" for="payment_qris">
                                        <i class="fas fa-qrcode me-2"></i>QRIS
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium text-dark">Jumlah Diterima</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0" style="border-radius: 8px 0 0 8px;">Rp</span>
                                <input type="number" name="amount_paid" class="form-control fw-bold" style="border-radius: 0 8px 8px 0;" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium text-dark">Kembalian</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0" style="border-radius: 8px 0 0 8px;">Rp</span>
                                <input type="text" id="change_amount" class="form-control fw-bold text-success" value="{{ number_format(max(0, ($total > 0 ? (request()->input('amount_paid', 0) - $total) : 0)), 0, ',', '.') }}" style="border-radius: 0 8px 8px 0;" readonly>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100" {{ empty($cart) ? 'disabled' : '' }} style="border-radius: 8px; padding: 12px; transition: all 0.3s;">
                            <i class="fas fa-check-circle me-2"></i>Proses Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Manual -->
<div class="modal fade" id="manualAddModal" tabindex="-1" aria-labelledby="manualAddModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header bg-dark text-white py-3">
                <h5 class="modal-title fw-semibold" id="manualAddModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Produk Manual
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('transactions.add-to-cart') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label for="shoe_select" class="form-label fw-medium text-dark">Pilih Sepatu dari Inventory</label>
                        <select class="form-select" id="shoe_select" name="barcode" required style="border-radius: 8px;">
                            <option value="">-- Pilih Sepatu --</option>
                            @forelse($shoes as $shoe)
                                <option value="{{ $shoe->barcode }}"
                                        data-name="{{ $shoe->name }}"
                                        data-price="{{ $shoe->price }}"
                                        data-stock="{{ $shoe->stock }}"
                                        data-qr="{{ $shoe->qrCode }}"
                                        {{ $shoe->stock <= 0 ? 'disabled' : '' }}>
                                    {{ $shoe->barcode }} - {{ $shoe->name }} (Stok: {{ $shoe->stock }})
                                </option>
                            @empty
                                <option value="" disabled>Tidak ada sepatu di inventory</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card bg-light border-0 shadow-sm" style="border-radius: 8px;">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted fw-medium">Detail Produk</h6>
                                    <div id="shoe_details" class="mt-3 p-3 bg-white rounded">
                                        <div class="text-center text-muted py-4">
                                            <i class="fas fa-shoe-prints fa-2x mb-2"></i>
                                            <p>Pilih sepatu untuk melihat detail</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light border-0 shadow-sm" style="border-radius: 8px;">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted fw-medium">QR Code</h6>
                                    <div id="qr_code_container" class="mt-3 p-3 bg-white rounded text-center">
                                        <div class="text-center text-muted py-4">
                                            <i class="fas fa-qrcode fa-2x mb-2"></i>
                                            <p>QR Code</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">
                        <i class="fas fa-times me-2"></i>Tutup
                    </button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 8px;">
                        <i class="fas fa-cart-plus me-2"></i>Tambah ke Keranjang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    body {
        background-color: #f4f6f9;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background-color: #1a73e8;
        border-color: #1a73e8;
    }

    .btn-primary:hover {
        background-color: #1557b0;
        border-color: #1557b0;
    }

    .btn-outline-info {
        border-color: #17a2b8;
        color: #17a2b8;
    }

    .btn-outline-info:hover {
        background-color: #17a2b8;
        color: #fff;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-success:hover {
        background-color: #218838;
        border-color: #218838;
    }

    .payment-method .payment-label {
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .payment-method .form-check-input {
        position: absolute;
        opacity: 0;
    }

    .payment-method .form-check-input:checked + .payment-label {
        background-color: #e7f1ff;
        border-color: #1a73e8;
        color: #1a73e8;
    }

    .table th, .table td {
        border: none;
    }

    .table thead th {
        background-color: #f8f9fa;
        color: #495057;
    }

    .table tfoot {
        border-top: 2px solid #dee2e6;
    }

    #camera-preview {
        border: 2px solid #1a73e8;
        box-shadow: 0 0 10px rgba(26, 115, 232, 0.2);
    }

    .scan-region-highlight {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 200px;
        height: 200px;
        transform: translate(-50%, -50%);
        border: 2px solid #1a73e8;
        border-radius: 8px;
        box-shadow: 0 0 0 2000px rgba(0, 0, 0, 0.3);
        pointer-events: none;
    }

    .scan-region-highlight::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background-color: rgba(26, 115, 232, 0.7);
        box-shadow: 0 0 5px rgba(26, 115, 232, 0.5);
        animation: scan 2s linear infinite;
    }

    @keyframes scan {
        0% { top: 0; }
        50% { top: 100%; }
        100% { top: 0; }
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script>
    document.querySelector('input[name="amount_paid"]').addEventListener('input', function() {
        const total = {{ $total }};
        const amountPaid = parseInt(this.value) || 0;
        const change = Math.max(0, amountPaid - total);
        const formatter = new Intl.NumberFormat('id-ID');
        document.getElementById('change_amount').value = formatter.format(change);
    });

    document.getElementById('shoe_select').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const name = selectedOption.getAttribute('data-name');
        const price = selectedOption.getAttribute('data-price');
        const stock = selectedOption.getAttribute('data-stock');
        const qrCode = selectedOption.getAttribute('data-qr');
        const detailsContainer = document.getElementById('shoe_details');
        const qrContainer = document.getElementById('qr_code_container');

        if (name && price && stock) {
            detailsContainer.innerHTML = `
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <div class="text-muted">Nama Produk</div>
                        <div class="fw-bold fs-5">${name}</div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="text-muted">Harga</div>
                        <div class="fw-bold text-primary fs-5">Rp ${parseInt(price).toLocaleString('id-ID')}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted">Stok Tersedia</div>
                        <div class="fw-bold ${parseInt(stock) > 5 ? 'text-success' : 'text-warning'} fs-5">${stock} unit</div>
                    </div>
                </div>
            `;
            qrContainer.innerHTML = qrCode ? qrCode : `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-qrcode fa-2x mb-2"></i>
                    <p>QR Code tidak tersedia</p>
                </div>
            `;
        } else {
            detailsContainer.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-shoe-prints fa-2x mb-2"></i>
                    <p>Pilih sepatu untuk melihat detail</p>
                </div>
            `;
            qrContainer.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-qrcode fa-2x mb-2"></i>
                    <p>QR Code</p>
                </div>
            `;
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const openCameraBtn = document.getElementById('open-camera-btn');
        const closeCameraBtn = document.getElementById('close-camera-btn');
        const cameraContainer = document.getElementById('camera-container');
        const videoElement = document.getElementById('camera-preview');
        const canvasElement = document.getElementById('canvas-capture');
        const canvas = canvasElement.getContext('2d');
        const qrcodeInput = document.getElementById('qrcode-input');
        const qrcodeForm = document.getElementById('qrcode-form');
        const cameraMessage = document.getElementById('camera-message');
        const cameraMessageText = document.getElementById('camera-message-text');
        
        let stream = null;
        let scanning = false;
        let lastScannedCode = null;
        let lastScannedTime = 0;

        function showMessage(message, isError = false) {
            cameraMessage.classList.remove('d-none', 'alert-info', 'alert-danger', 'alert-success');
            cameraMessage.classList.add(isError ? 'alert-danger' : 'alert-info');
            cameraMessageText.textContent = message;
            cameraMessage.classList.remove('d-none');
        }

        function showSuccessMessage(message) {
            cameraMessage.classList.remove('d-none', 'alert-info', 'alert-danger');
            cameraMessage.classList.add('alert-success');
            cameraMessageText.textContent = message;
            cameraMessage.classList.remove('d-none');
        }

        function processQRCode(code) {
            const currentTime = new Date().getTime();
            if (code !== lastScannedCode || (currentTime - lastScannedTime) > 2000) {
                lastScannedCode = code;
                lastScannedTime = currentTime;
                qrcodeInput.value = code;
                showSuccessMessage('QR Code terdeteksi: ' + code + '. Mengirim data...');
                setTimeout(() => qrcodeForm.submit(), 1000);
            }
        }

        function startScanning() {
            if (scanning) return;
            scanning = true;
            function tick() {
                if (!scanning) return;
                if (videoElement.readyState === videoElement.HAVE_ENOUGH_DATA) {
                    canvasElement.height = videoElement.videoHeight;
                    canvasElement.width = videoElement.videoWidth;
                    canvas.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);
                    const imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
                    try {
                        const code = jsQR(imageData.data, imageData.width, imageData.height, { inversionAttempts: "dontInvert" });
                        if (code && code.data) processQRCode(code.data);
                    } catch (e) {
                        console.error("Error scanning QR Code:", e);
                    }
                }
                requestAnimationFrame(tick);
            }
            tick();
        }

        function startCamera() {
            cameraContainer.classList.remove('d-none');
            showMessage('Meminta izin kamera...');
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                showMessage('Browser tidak mendukung kamera.', true);
                return;
            }
            const constraints = { video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } } };
            navigator.mediaDevices.getUserMedia(constraints)
                .then(mediaStream => {
                    stream = mediaStream;
                    videoElement.srcObject = stream;
                    videoElement.onloadedmetadata = () => {
                        videoElement.play();
                        showMessage('Arahkan kamera ke QR Code produk');
                        startScanning();
                    };
                })
                .catch(err => {
                    let errorMessage = 'Gagal mengakses kamera.';
                    if (err.name === 'NotAllowedError') errorMessage = 'Izin kamera ditolak.';
                    else if (err.name === 'NotFoundError') errorMessage = 'Kamera tidak ditemukan.';
                    showMessage(errorMessage, true);
                });
        }

        function stopCamera() {
            scanning = false;
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
                videoElement.srcObject = null;
            }
            cameraContainer.classList.add('d-none');
        }

        openCameraBtn.addEventListener('click', startCamera);
        closeCameraBtn.addEventListener('click', stopCamera);
        window.addEventListener('beforeunload', stopCamera);
        document.addEventListener('shown.bs.modal', stopCamera);
    });
</script>
@endsection