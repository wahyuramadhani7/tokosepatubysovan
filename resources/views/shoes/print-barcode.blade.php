<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR Code - {{ $shoe->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .barcode-container {
            border: 1px solid #ccc;
            padding: 10px;
            width: 200px;
            text-align: center;
            margin-bottom: 20px;
        }
        .barcode-container h5 {
            margin: 5px 0;
            font-size: 14px;
        }
        .barcode-container p {
            margin: 3px 0;
            font-size: 12px;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="barcode-container">
        <h5>{{ $shoe->name }}</h5>
        <p>Ukuran: {{ $shoe->size }}</p>
        <p>Harga: Rp {{ number_format($shoe->price, 0, ',', '.') }}</p>
        <p>Barcode: {{ $shoe->barcode }}</p>
        {!! $qrCode !!} <!-- Menampilkan QR Code yang dihasilkan -->
    </div>

    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary">Cetak QR Code</button>
        <a href="{{ route('shoes.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</body>
</html>