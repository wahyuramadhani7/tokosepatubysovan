<!DOCTYPE html>
<html>
<head>
    <title>Detail Sepatu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .container {
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
            font-family: Arial, sans-serif;
        }
        .shoe-detail {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }
        .shoe-detail h1 {
            margin-top: 0;
            color: #333;
        }
        .detail-item {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="shoe-detail">
            <h1>{{ $shoe->name }}</h1>
            <div class="detail-item">
                <strong>Barcode:</strong> {{ $shoe->barcode }}
            </div>
            <div class="detail-item">
                <strong>Ukuran:</strong> {{ $shoe->size }}
            </div>
            <div class="detail-item">
                <strong>Harga:</strong> Rp {{ number_format($shoe->price, 0, ',', '.') }}
            </div>
            <div class="detail-item">
                <strong>Stok:</strong> {{ $shoe->stock }}
            </div>
        </div>
    </div>
</body>
</html>