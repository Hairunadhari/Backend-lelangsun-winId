<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="row m-5">
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <form>
                        <h3>Maps Api</h3>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Alamat Asal</label>
                            <input type="text" class="form-control" id="alamatasal">
                            <div class="btn btn-sm btn-primary my-1" id="cari">Cari</div>
                            <select class="form-select" style="display: none;" id="dropdown-alamat-asal">
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Alamat Tujuan</label>
                            <input type="text" class="form-control" id="alamattujuan">
                            <div class="btn btn-sm btn-primary my-1" id="cari2">Cari</div>
                            <select class="form-select" style="display: none;" id="dropdown-alamat-tujuan">
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <form>
                        <h3>Courier Api</h3>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">List Kurir</label>
                            <select name="" id="dropdown-kurir" class="form-select"></select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <form action="#" method="POST" enctype="multipart/form-data">
                        @csrf
                        <h3>Rates Api</h3>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Alamat asal</label>
                            <input type="text" class="form-control" id="alamat-asal" name="origin_id"
                                aria-describedby="emailHelp" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Alamat tujuan</label>
                            <input type="text" class="form-control" id="alamat-tujuan" name="destination_id"
                                aria-describedby="emailHelp" readonly>
                        </div>
                        <div id="submit" class="btn btn-success">Cek Harga Pengiriman</div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card" id="ongkir">
                    <div class="card-body">
                        <form>
                            <h3>Ongkis Kirim</h3>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>
<script>
    $(document).ready(function () {
        $(document).on('click', '#cari', function () {
            value = $('#alamatasal').val();
            $.ajax({
                method: 'get',
                url: '/tescariarea/' + value,
                success: function (res) {
                    $('#dropdown-alamat-asal').show();
                    $.each(res.areas, function (index, area) {
                        // console.log(area.id);
                        $('#dropdown-alamat-asal').append('<option value="' + area
                            .id +
                            '">' + area.name + '</option>');
                    });
                },

                error: function (error) {
                    console.error('Error saat mengambil data:', error);
                }
            });
        });

        $(document).on('click', '#cari2', function () {
            value = $('#alamattujuan').val();
            $.ajax({
                method: 'get',
                url: '/tescariarea/' + value,
                success: function (res) {
                    $('#dropdown-alamat-tujuan').show();
                    $.each(res.areas, function (index, area) {
                        // console.log(area.id);
                        $('#dropdown-alamat-tujuan').append('<option value="' + area
                            .id +
                            '">' + area.name + '</option>');
                    });
                },

                error: function (error) {
                    console.error('Error saat mengambil data:', error);
                }
            });
        });

        // $.ajax({
        //     method: 'get',
        //     url: '/teslistkurir',
        //     success: function (res) {
        //         // Pastikan bahwa res.couriers adalah yang akan diiterasi
        //         $.each(res.couriers, function (index, courier) {
        //             // Gunakan properti yang sesuai dalam objek kurir
        //             $('#dropdown-kurir').append('<option value="' + courier.courier_code +
        //                 '">' + courier.courier_service_name + ' , ' + courier
        //                 .shipment_duration_range + ' ' + courier
        //                 .shipment_duration_unit + '</option>');
        //         });
        //     }
        // });

        $('#dropdown-alamat-asal').on('change', function () {
            value = $(this).val();
            // console.log(value);
            $('#alamat-asal').val(value);

        })
        $('#dropdown-alamat-tujuan').on('change', function () {
            value = $(this).val();
            // console.log(value);
            $('#alamat-tujuan').val(value);

        })

        $(document).on('click', '#submit', function () {
            asal = $('#alamat-asal').val();
            tujuan = $('#alamat-tujuan').val();
            // asal = 'IDNP9IDNC111IDND272IDZ16455';
            // tujuan = 'IDNP9IDNC111IDND266IDZ16513';
            $.ajax({
                method: 'get',
                url: '/tescekongkir/' + asal + '/' + tujuan,
                success: function (res) {
                    console.log(res);
                    $.each(res.pricing, function (key, value) {
                        $('#ongkir').append(`<div class="m-4">
                                <input type="text" class="form-control" id="isian-ongkir"
                                aria-describedby="emailHelp" value="Kurir: ${value.courier_service_name}, est: ${value.duration}, harga: ${value.price}" readonly>
                            </div>`);
                    });
                },

                error: function (error) {
                    console.error('Error saat mengambil data:', error);
                }
            });
        });

    });

</script>

</html>
