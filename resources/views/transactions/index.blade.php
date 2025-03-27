@extends('layouts.app')

@section('title', 'Penjualan')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-primary fw-bold">
        <i class="fas fa-shopping-cart me-2"></i>Point of Sale
    </h2>

    <div class="row g-4">
        <!-- Scan Produk dan Keranjang -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-qrcode me-2"></i>Scan Produk
                    </h5>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <form action="{{ route('transactions.add-to-cart') }}" method="POST" id="barcode-form">
                                @csrf
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light"><i class="fas fa-qrcode"></i></span>
                                    <input type="text" name="barcode" id="barcode-input" class="form-control form-control-lg" placeholder="Masukkan barcode produk" required autofocus>
                                    <button class="btn btn-primary px-4" type="submit">
                                        <i class="fas fa-qrcode me-2"></i>Scan
                                    </button>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-info-circle me-1"></i> Masukkan barcode produk atau gunakan tombol "Buka Kamera"
                                </small>
                            </form>
                        </div>
                        
                        <div class="col-md-4 d-flex align-items-center justify-content-center justify-content-md-end mt-3 mt-md-0">
                            <button class="btn btn-info px-4" id="open-camera-btn">
                                <i class="fas fa-camera me-2"></i>Buka Kamera
                            </button>
                        </div>
                    </div>
                    
                    <!-- Video Preview untuk Scan QR -->
                    <div id="camera-container" class="row mb-4 d-none">
                        <div class="col-12">
                            <div class="card border-info shadow-sm">
                                <div class="card-header bg-info bg-opacity-10 py-2 d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-info"><i class="fas fa-camera me-2"></i>QR Scanner</span>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="close-camera-btn">
                                        <i class="fas fa-times"></i> Tutup
                                    </button>
                                </div>
                                <div class="card-body p-2 text-center position-relative">
                                    <video id="camera-preview" autoplay playsinline style="width: 100%; max-height: 300px; border-radius: 8px;"></video>
                                    <canvas id="canvas-capture" style="display: none;"></canvas>
                                    <div class="scan-region-highlight"></div>
                                    <div class="scan-region-highlight-svg"></div>
                                    <div class="alert alert-info mt-2 mb-0" id="camera-message">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <span id="camera-message-text">Sedang mengaktifkan kamera...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-shopping-basket me-2"></i>Keranjang Belanja
                        </h5>
                        <div>
                            <button class="btn btn-outline-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#manualAddModal">
                                <i class="fas fa-plus me-1"></i>Tambah Manual
                            </button>
                            <a href="{{ route('transactions.cancel') }}" class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin ingin membatalkan transaksi?')">
                                <i class="fas fa-trash me-1"></i>Kosongkan
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Barcode</th>
                                    <th>Produk</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cart as $item)
                                    <tr>
                                        <td><span class="badge bg-light text-dark">{{ $item['barcode'] }}</span></td>
                                        <td class="fw-medium">{{ $item['name'] }}</td>
                                        <td class="text-end">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary rounded-pill">{{ $item['quantity'] }}</span>
                                        </td>
                                        <td class="text-end fw-bold">Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                            <p>Keranjang belanja masih kosong</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-group-divider">
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">TOTAL</td>
                                    <td class="text-end fs-5 fw-bold text-primary">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pembayaran -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 sticky-top" style="top: 15px; z-index: 100;">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-credit-card me-2"></i>Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('transactions.checkout') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-medium">Info Pelanggan</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                <input type="text" name="customer_name" class="form-control" placeholder="Nama Pelanggan">
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                                <input type="text" name="customer_phone" class="form-control" placeholder="No. Telepon (opsional)">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-medium">Metode Pembayaran</label>
                            <div class="d-flex flex-wrap gap-2">
                                <div class="form-check payment-method">
                                    <input type="radio" name="payment_method" value="tunai" class="form-check-input" id="payment_cash" required>
                                    <label class="form-check-label payment-label p-2 rounded" for="payment_cash">
                                        <i class="fas fa-money-bill-wave me-2"></i>Tunai
                                    </label>
                                </div>
                                <div class="form-check payment-method">
                                    <input type="radio" name="payment_method" value="debit_kredit" class="form-check-input" id="payment_card">
                                    <label class="form-check-label payment-label p-2 rounded" for="payment_card">
                                        <i class="fas fa-credit-card me-2"></i>Debit/Kredit
                                    </label>
                                </div>
                                <div class="form-check payment-method">
                                    <input type="radio" name="payment_method" value="qris" class="form-check-input" id="payment_qris">
                                    <label class="form-check-label payment-label p-2 rounded" for="payment_qris">
                                        <i class="fas fa-qrcode me-2"></i>QRIS
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-medium">Jumlah Diterima</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="number" name="amount_paid" class="form-control form-control-lg fw-bold" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-medium">Kembalian</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="text" id="change_amount" class="form-control form-control-lg fw-bold text-success" value="{{ number_format(max(0, ($total > 0 ? (request()->input('amount_paid', 0) - $total) : 0)), 0, ',', '.') }}" readonly>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-success btn-lg w-100" {{ empty($cart) ? 'disabled' : '' }}>
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
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="manualAddModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Produk Manual
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('transactions.add-to-cart') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label for="shoe_select" class="form-label fw-medium">Pilih Sepatu dari Inventory</label>
                        <select class="form-select form-select-lg" id="shoe_select" name="barcode" required>
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
                            <div class="card bg-light border-0 rounded-3 mb-3">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">Detail Produk</h6>
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
                            <div class="card bg-light border-0 rounded-3 mb-3">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">QR Code</h6>
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
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Tutup
                    </button>
                    <button type="submit" class="btn btn-primary">
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
    .payment-method .payment-label {
        border: 1px solid #dee2e6;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .payment-method .form-check-input {
        position: absolute;
        opacity: 0;
    }
    
    .payment-method .form-check-input:checked + .payment-label {
        background-color: #e7f1ff;
        border-color: #0d6efd;
        color: #0d6efd;
    }
    
    .table td, .table th {
        vertical-align: middle;
    }
    
    /* Scanner styling */
    #camera-preview {
        border: 2px solid #f28c38;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(242, 140, 56, 0.3);
    }
    
    .scan-region-highlight {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 200px;
        height: 200px;
        transform: translate(-50%, -50%);
        border: 2px solid #f28c38;
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
        background-color: rgba(242, 140, 56, 0.7);
        box-shadow: 0 0 5px rgba(242, 140, 56, 0.5);
        animation: scan 2s linear infinite;
        z-index: 10;
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
    // Script untuk menghitung kembalian secara real-time
    document.querySelector('input[name="amount_paid"]').addEventListener('input', function() {
        const total = {{ $total }};
        const amountPaid = parseInt(this.value) || 0;
        const change = Math.max(0, amountPaid - total);
        
        // Format sebagai rupiah
        const formatter = new Intl.NumberFormat('id-ID');
        document.getElementById('change_amount').value = formatter.format(change);
    });

    // Script untuk menampilkan detail sepatu dan QR code saat dipilih
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
            
            if (qrCode) {
                qrContainer.innerHTML = qrCode;
            } else {
                qrContainer.innerHTML = `
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-qrcode fa-2x mb-2"></i>
                        <p>QR Code tidak tersedia</p>
                    </div>
                `;
            }
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

    // QR Code Scanner dengan jsQR
    document.addEventListener('DOMContentLoaded', function() {
        const openCameraBtn = document.getElementById('open-camera-btn');
        const closeCameraBtn = document.getElementById('close-camera-btn');
        const cameraContainer = document.getElementById('camera-container');
        const videoElement = document.getElementById('camera-preview');
        const canvasElement = document.getElementById('canvas-capture');
        const canvas = canvasElement.getContext('2d');
        const barcodeInput = document.getElementById('barcode-input');
        const barcodeForm = document.getElementById('barcode-form');
        const cameraMessage = document.getElementById('camera-message');
        const cameraMessageText = document.getElementById('camera-message-text');
        
        let stream = null;
        let scanning = false;
        let lastScannedCode = null;
        let lastScannedTime = 0;

        // Fungsi untuk menampilkan pesan
        function showMessage(message, isError = false) {
            cameraMessage.classList.remove('d-none', 'alert-info', 'alert-danger', 'alert-success');
            cameraMessage.classList.add(isError ? 'alert-danger' : 'alert-info');
            cameraMessageText.textContent = message;
            cameraMessage.classList.remove('d-none');
        }

        // Fungsi untuk menampilkan pesan sukses
        function showSuccessMessage(message) {
            cameraMessage.classList.remove('d-none', 'alert-info', 'alert-danger');
            cameraMessage.classList.add('alert-success');
            cameraMessageText.textContent = message;
            cameraMessage.classList.remove('d-none');
        }

        // Fungsi untuk menyembunyikan pesan
        function hideMessage() {
            cameraMessage.classList.add('d-none');
        }

        // Fungsi untuk memproses hasil scan QR Code
        function processQRCode(code) {
            const currentTime = new Date().getTime();
            
            // Cek apakah kode baru atau scan yang sama dalam waktu singkat (hindari duplikat scan)
            if (code !== lastScannedCode || (currentTime - lastScannedTime) > 2000) {
                lastScannedCode = code;
                lastScannedTime = currentTime;
                
                // Masukkan hasil scan ke input barcode
                barcodeInput.value = code;
                
                // Tampilkan pesan sukses
                showSuccessMessage('QR Code terdeteksi: ' + code + '. Mengirim data...');
                
                // Delay sedikit untuk memastikan pengguna melihat pesan sukses
                setTimeout(() => {
                    // Submit form
                    barcodeForm.submit();
                }, 1000);
            }
        }

        // Fungsi untuk memulai scan QR Code
        function startScanning() {
            if (scanning) return;
            scanning = true;
            
            // Fungsi untuk menganalisis frame video
            function tick() {
                if (!scanning) return;
                
                if (videoElement.readyState === videoElement.HAVE_ENOUGH_DATA) {
                    // Atur ukuran canvas sesuai video
                    canvasElement.height = videoElement.videoHeight;
                    canvasElement.width = videoElement.videoWidth;
                    
                    // Gambar frame video ke canvas
                    canvas.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);
                    
                    // Ambil data gambar
                    const imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
                    
                    // Scan QR code
                    try {
                        const code = jsQR(imageData.data, imageData.width, imageData.height, {
                            inversionAttempts: "dontInvert",
                        });
                        
                        if (code) {
                            console.log("QR Code detected:", code.data);
                            
                            // Process QR code data
                            if (code.data) {
                                processQRCode(code.data);
                            }
                        }
                    } catch (e) {
                        console.error("Error scanning QR code:", e);
                    }
                }
                
                // Continue scanning
                requestAnimationFrame(tick);
            }
            
            // Start continuous scanning
            tick();
        }

        // Fungsi untuk membuka kamera
        function startCamera() {
            cameraContainer.classList.remove('d-none');
            showMessage('Meminta izin kamera...');

            // Cek apakah browser mendukung WebRTC API
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                showMessage('Browser Anda tidak mendukung penggunaan kamera. Silakan gunakan browser modern seperti Chrome, Firefox, atau Safari terbaru.', true);
                return;
            }

            // Opsi untuk memilih kamera belakang jika tersedia
            const constraints = {
                video: {
                    facingMode: 'environment', // Kamera belakang
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                }
            };

            // Minta izin kamera
            navigator.mediaDevices.getUserMedia(constraints)
                .then(function(mediaStream) {
                    stream = mediaStream;
                    videoElement.srcObject = stream;
                    videoElement.onloadedmetadata = function(e) {
                        videoElement.play();
                        showMessage('Arahkan kamera ke QR Code produk');
                        // Mulai scan QR code
                        startScanning();
                    };
                })
                .catch(function(err) {
                    console.error('Gagal mengakses kamera:', err.name, err.message);
                    let errorMessage = 'Gagal mengakses kamera.';
                    
                    if (err.name === 'NotAllowedError') {
                        errorMessage = 'Izin kamera ditolak. Silakan izinkan akses kamera untuk menggunakan fitur ini.';
                    } else if (err.name === 'NotFoundError') {
                        errorMessage = 'Kamera tidak ditemukan pada perangkat Anda.';
                    } else if (err.name === 'NotReadableError') {
                        errorMessage = 'Kamera sedang digunakan oleh aplikasi lain. Silakan tutup aplikasi lain yang menggunakan kamera.';
                    } else if (err.name === 'OverconstrainedError') {
                        errorMessage = 'Kamera dengan spesifikasi yang diminta tidak tersedia.';
                    } else if (err.name === 'TypeError') {
                        errorMessage = 'Tidak ada kamera yang tersedia atau layanan kamera dinonaktifkan.';
                    }
                    
                    showMessage(errorMessage, true);
                });
        }

        // Fungsi untuk menutup kamera
        function stopCamera() {
            scanning = false;
            
            if (stream) {
                stream.getTracks().forEach(function(track) {
                    track.stop();
                });
                stream = null;
                videoElement.srcObject = null;
            }
            cameraContainer.classList.add('d-none');
        }

        // Event listener untuk tombol buka kamera
        openCameraBtn.addEventListener('click', startCamera);

        // Event listener untuk tombol tutup kamera
        closeCameraBtn.addEventListener('click', stopCamera);

        // Tutup kamera saat navigasi keluar halaman
        window.addEventListener('beforeunload', stopCamera);
        
        // Tutup kamera saat modal terbuka
        document.addEventListener('shown.bs.modal', stopCamera);
        
        // Fungsi untuk ekstraksi barcode dari SVG QR Code
        // Fungsi ini mencoba mendapatkan barcode dari QR code yang di-generate oleh sistem inventory
        window.extractBarcodeFromSVG = function(svgElement) {
            // Cek apakah ada tag title dalam SVG (biasanya berisi data)
            const title = svgElement.querySelector('title');
            if (title && title.textContent) {
                return title.textContent;
            }
            
            // Cek atribut data
            if (svgElement.dataset && svgElement.dataset.value) {
                return svgElement.dataset.value;
            }
            
            // Jika tidak ada cara mudah untuk mendapatkan data, gunakan teknik regex
            // untuk ekstrak data dari struktur QR code
            const svgContent = svgElement.outerHTML;
            
            // Berbagai pattern yang mungkin digunakan oleh generator QR code
            const patterns = [
                /<title>(.*?)<\/title>/i,
                /data-value="(.*?)"/i,
                /data-content="(.*?)"/i,
                /content="(.*?)"/i,
                /value="(.*?)"/i
            ];
            
            for (const pattern of patterns) {
                const match = svgContent.match(pattern);
                if (match && match[1]) {
                    return match[1];
                }
            }
            
            return null;
        };
    });
</script>
@endsection