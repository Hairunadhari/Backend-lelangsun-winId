<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <title>WIN</title>
</head>
<body>
    <div class="card m-5">
        <div class="card-header">
            <h4 class="text-center">Win Event</h4>
        </div>
        <div class="card-body">
            <div style="display:flex; justify-content:center; align-items:center; width:100%; height:auto;" class="m-3">{!! $barcodeHTML !!}</div>
            <p>Halooo {{ $data->nama }} Selamat akun anda sudah terverifikasi! Info tentang event {{ $data->event->judul }} sebagai berikut:</p>
            <p>Nama Event : {{ $data->event->judul }}</p>
            <p>Link Meeting : {{ $data->event->link }}</p>
            <p>Alamat Lokasi : {{ $data->event->alamat_lokasi }}</p>
            <p>Link Lokasi : {{ $data->event->link_lokasi }}</p>
        </div>
    </div>

    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
</body>
</html>
