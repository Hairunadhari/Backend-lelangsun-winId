@extends('front-end.layout')
@section('content')
<style>
    .con-kontak {
        background-image: url('asset-lelang/kontak1.png');
        height: 35vh;
        background-position: center;
        background-size: cover;
        width: 100%;
        padding: 150px 50px 50px 50px;
        color: white;
        text-align: center;
    }

    .parent {
        padding: 50px;
        color: white;
        text-align: center;
        display: flex;
        justify-content: center;
    }

    .con-kontak2 {
        background-image: url('asset-lelang/lelang2.jpg');
        height: auto;
        background-position: center;
        background-size: cover;
        width: 100%;
        display: flex;
        justify-content: center;
        padding-top: 100px;
        padding-bottom: 100px;
    }

    .card-kon,
    .card-kon2,
    .card-kon3 {
        background-color: #31869b;
        padding: 40px 40px 10px 40px;
    }

    .card-kon2,
    .card-kon3 {
        margin-left: 40px;
    }

    .a {
        width: 500px;
    }

    .lokasi {
        display: flex;
        justify-content: center;
    }

    .lokasi iframe {
        width: 100%;
        padding: 0px 100px 100px 100px;
    }

    .scroll {
        height: 500px;
        overflow: scroll;
    }

    .heads {
        display: flex;
        justify-content: space-between;
        padding: 0px 10px 10px 10px;
    }

    .button {
        width: 300px;
    }

    .heads a {
        text-decoration: none;
        color: black;
    }

    @media (max-width: 600px) {
        .parent {
            padding: 20px;
            color: white;
            text-align: center;
            display: block;
        }

        .a {
            width: 200px;
        }

        .card-kon2,
        .card-kon3 {
            margin-top: 10px;
            margin-left: 0px;
        }

        .lokasi iframe {
            width: 100%;
            padding: 20px;
        }
    }

</style>
<section id="kontak">
    <div class="con-kontak">
        <img src="{{ asset('asset-lelang/profile_picture.png') }}" width="150" alt="">
    </div>
    <div class="con-kontak2">
        <div class="card" style="width: 80%;">
            <div class="card-body">
                <div class="heads">
                    <a href="{{route('front-end-notif')}}">Profile</a>
                    <a href="{{route('front-end-npl')}}">NPL</a>
                    <a href="{{route('front-end-pelunasan')}}">Pelunasan Barang Lelang</a>
                    <a href="{{route('front-end-pesan')}}">Notifikasi</a>
                </div>
                <form>
                    <div class="row">
                        <div class="col-6">
                            <label for="" class="form-label">Nama</label>
                            <input type="email" class="form-control" id="">
                        </div>
                        <div class="col-6">
                            <label for="" class="form-label">No Hp</label>
                            <input type="email" class="form-control" id="" aria-describedby="emailHelp">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="" class="form-label">No Rekening</label>
                            <input type="email" class="form-control" id="">
                        </div>
                        <div class="col-6">
                            <label for="" class="form-label">NIK</label>
                            <input type="email" class="form-control" id="" aria-describedby="emailHelp">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="" class="form-label">Nama Pemilik Rekening</label>
                            <input type="email" class="form-control" id="">
                        </div>
                        <div class="col-6">
                            <label for="" class="form-label">Foto KTP</label>
                            <input type="email" class="form-control" id="" aria-describedby="emailHelp">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Bogor</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="alert alert-warning" role="alert">
                                Cara untuk mengecilkan ukuran foto dalam HP Android, ikuti langkah-langkah berikut.
                                <br>
                                <p>1. Ketuk <strong>Kamera</strong></p>
                                <p>2. Buka <strong>Menu</strong></p>
                                <p>3. Ketuk <strong>Pengaturan</strong></p>
                                <p>4. Ketuk <strong>Kualitas Gambar</strong></p>
                                <p>5. Tetapkan <strong>Pengaturan Renda</strong></p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="alert alert-warning" role="alert">
                                Cara untuk mengecilkan ukuran foto dalam IOS, ikuti langkah-langkah berikut.
                                <br>
                                <p>1. Buka <strong>Pengaturan</strong> di Iphone anda</p>
                                <p>2. Swipe jebawah hingga menemukan opsi <strong>Kamera</strong></p>
                                <p>3. Ketuk <strong>Format</strong></p>
                                <p>4. Tetapkan Pengaturan <strong>Efisiensi Tinggi</strong></p>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger">Simpan</button>
                </form>
            </div>
        </div>
    </div>


</section>
@endsection
