<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>checkout</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Halaman Checkout</h2>
            </div>
        </div>
        <form class="ps-checkout__form" action="" method="post">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group ">
                        <label>Alamat<span>*</span>
                        </label>
                        <textarea name="address" class="form-control" rows="5"
                            placeholder="Alamat Lengkap pengiriman"></textarea>
                    </div>
                    <div class="form-group form-group--inline">
                        <label for="provinsi">Provinsi Tujuan</label>
                        <select name="province_id" id="province_id" class="form-control">
                            <option value="">Provinsi Tujuan</option>
                            @foreach ($provinsi as $row)
                            <option value="{{$row['province_id']}}" namaprovinsi="{{$row['province']}}">{{$row['province']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <input class="form-control" type="hidden" value="1" id="weight" name="city_origin">
                    <input type="hidden" class="form-control" id="nama_provinsi" nama="nama_provinsi" placeholder="ini untuk menangkap nama provinsi ">
                    <div class="form-group ">
                        <label>Kota Tujuan<span>*</span>
                        </label>
                        <select name="kota_id" id="kota_id" class="form-control">
                            <option value="">Pilih Kota</option>
                        </select>
                    </div>
                    <div class="form-group ">
                        <label>Pilih Ekspedisi<span>*</span>
                        </label>
                        <select name="kurir" id="kurir" class="form-control">
                            <option value="">Pilih kurir</option>
                            <option value="jne">JNE</option>
                            <option value="tiki">TIKI</option>
                            <option value="pos">POS INDONESIA</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pilih Layanan<span>*</span>
                        </label>
                        <select name="layanan" id="layanan" class="form-control">
                            <option value="">Pilih layanan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="nama_kota" name="nama_kota" placeholder="ini untuk menangkap nama kota">
                    </div>
                    <div class="form-group ">
                        <label>Kode Pos<span>*</span>
                        </label>
                        <input type="text" name="kode_pos" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group ">
                        <label>Total Belanja<span>*</span>
                        </label>
                        <input type="text" name="totalbelanja" class="form-control">
                    </div>
                    <div class="form-group ">
                        <label>total berat (gram) </label>
                        <input class="form-control" type="text" value="200" id="weight" name="weight">

                    </div>
                    <div class="form-group ">
                        <label>total ongkos kirim </label>
                        <input class="form-control" type="text" id="ongkos_kirim" name="ongkos_kirim">
                    </div>
                    <div class="form-group ">
                        <label>total keseluruhan </label>
                        <input class="form-control" type="text" id="ongkos_kirim" name="ongkos_kirim">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">Proses Order</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function () {
            //ini ketika provinsi tujuan di klik maka akan eksekusi perintah yg kita mau
            //name select nama nya "provinve_id" kalian bisa sesuaikan dengan form select kalian
            $('select[name="province_id"]').on('change', function(){
                // membuat variable namaprovinsiku untyk mendapatkan atribut nama provinsi
                var namaprovinsiku = $("#province_id option:selected").attr("namaprovinsi");
                // menampilkan hasil nama provinsi ke input id nama_provinsi
                $("#nama_provinsi").val(namaprovinsiku);
                // kita buat variable provincedid untk menampung data id select province
                let provinceid = $(this).val();
                //kita cek jika id di dpatkan maka apa yg akan kita eksekusi
                if (provinceid) {
                    // jika di temukan id nya kita buat eksekusi ajax GET
                    jQuery.ajax({
                        // url yg di root yang kita buat tadi
                        url: "/kota/" + provinceid,
                        // aksion GET, karena kita mau mengambil data
                        type: 'GET',
                        // type data json
                        dataType: 'json',
                        // jika data berhasil di dapat maka kita mau apain nih
                        success: function (data) {
                            // jika tidak ada select dr provinsi maka select kota kososng / empty
                            $('select[name="kota_id"]').empty();
                            // jika ada kita looping dengan each
                            $.each(data, function (key, value) {
                                // perhtikan dimana kita akan menampilkan data select nya, di sini saya memberi name select kota adalah kota_id
                                $('select[name="kota_id"]').append(
                                    '<option value="' + value.city_id +
                                    '" namakota="' + value.type + ' ' + value
                                    .city_name + '">' + value.type + ' ' + value
                                    .city_name + '</option>');
                            });
                        }
                    });
                } else {
                    $('select[name="kota_id"]').empty();
                }
            });
            //memberikan action ketika select name kota_id di select
            //memberikan action ketika select name kota_id di select
            $('select[name="kota_id"]').on('change', function(){
                // membuat variable namakotaku untyk mendapatkan atribut nama kota
                var namakotaku = $("#kota_id option:selected").attr("namakota");
                // menampilkan hasil nama provinsi ke input id nama_provinsi
                $("#nama_kota").val(namakotaku);
            });

            $('select[name="kurir"]').on('change', function () {
                // kita buat variable untuk menampung data data dari  inputan
                // name city_origin di dapat dari input text name city_origin
                let origin = $("input[name=city_origin]").val();
                // name kota_id di dapat dari select text name kota_id
                let destination = $("select[name=kota_id]").val();
                // name kurir di dapat dari select text name kurir
                let courier = $("select[name=kurir]").val();
                // name weight di dapat dari select text name weight
                let weight = $("input[name=weight]").val();
                // alert(courier);
                if (courier) {
                    jQuery.ajax({
                        url: "/origin=" + origin + "&destination=" + destination + "&weight=" +
                            weight + "&courier=" + courier,
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            $('select[name="layanan"]').empty();
                            // ini untuk looping data result nya
                            $.each(data, function (key, value) {
                                console.log(data);
                                // ini looping data layanan misal jne reg, jne oke, jne yes
                                $.each(value.costs, function (key1, value1) {
                                    // ini untuk looping cost nya masing masing
                                    // silahkan pelajari cara menampilkan data json agar lebi paham
                                    $.each(value1.cost, function (key2,
                                        value2) {
                                        $('select[name="layanan"]')
                                            .append(
                                                '<option value="' +
                                                key + '">' + value1
                                                .service + '-' +
                                                value1.description +
                                                '-' + value2.value +
                                                '</option>');
                                    });
                                });
                            });
                        }
                    });
                } else {
                    $('select[name="layanan"]').empty();
                }
            });
        });

    </script>
</body>

</html>
