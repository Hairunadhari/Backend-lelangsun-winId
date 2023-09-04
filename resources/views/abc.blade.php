<!DOCTYPE html>
<html>
<head>
    <title>QR Code</title>
</head>
<body>
    <!-- Menampilkan gambar QR code -->
    <div class="mb-3">{!! DNS2D::getBarcodeHTML("$data", 'QRCODE') !!}</div>
</body>
</html>
