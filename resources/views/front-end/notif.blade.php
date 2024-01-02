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
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                @endif
            <div class="card-body">
                <div class="heads">
                    <a class="{{ request()->routeIs('front-end-notif') ? 'badge text-bg-danger' : '' }}" href="{{route('front-end-notif')}}">Profile</a>
                    <a href="{{route('front-end-npl')}}">NPL</a>
                    <a href="{{route('front-end-pelunasan')}}">Pelunasan Barang Lelang</a>
                    <a href="{{route('front-end-pesan')}}">Notifikasi</a>
                </div>
                <form action="{{route('edit-profil-user', $data->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="" class="form-label">Nama</label>
                            <input type="text" class="form-control" name="name" value="{{$data->name}}">
                        </div>
                        <div class="col-6">
                            <label for="" class="form-label">No Hp</label>
                            <input type="number" class="form-control" name="no_telp" value="{{$data->no_telp}}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="" class="form-label">No Rekening</label>
                            <input type="text" class="form-control" name="no_rek" value="{{$data->no_rek}}">
                        </div>
                        <div class="col-6">
                            <label for="" class="form-label">NIK</label>
                            <input type="text" class="form-control" name="nik" value="{{$data->nik}}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Alamat</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" name="alamat" rows="3">{{$data->alamat}}</textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6"><img src="{{ asset('storage/image/'.$data->foto_ktp) }}" width="150" height="150" style=" box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;"></div>
                        <div class="col-6"><img src="{{ asset('storage/image/'.$data->foto_npwp) }}" width="150" height="150" style=" box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;"></div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label for="" class="form-label">Foto KTP</label>
                            <input type="file" class="form-control" name="foto_ktp" accept=".jpg,.png,.jpeg" id="gambarktp">
                            <div id="previewktp" class="mt-3"></div>
                        </div>
                        <div class="col-6">
                            <label for="" class="form-label">Foto NPWP</label>
                            <input type="file" class="form-control" name="foto_npwp" accept=".jpg,.png,.jpeg" id="gambarnpwp">
                            <div id="previewnpwp" class="mt-3"></div>
                        </div>
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
<script>
    function previewgambarktp() {
        var preview = document.querySelector('#previewktp');

        // Hapus semua elemen child di dalam elemen #preview
        while (preview.firstChild) {
            preview.removeChild(preview.firstChild);
        }

        if (this.files) {
            [].forEach.call(this.files, readAndPreview);
        }

        function readAndPreview(file) {
            if (!/\.(jpe?g|png|jpg)$/i.test(file.name)) {
                alert("Hanya file gambar dengan ekstensi .jpeg, .jpg, .png, yang diperbolehkan.");
                document.querySelector('#gambarktp').value = '';
                return;
            }
            var reader = new FileReader();
            reader.addEventListener("load", function () {
                var image = new Image();
                image.width = 150;
                image.height = 150;
                image.title = file.name;
                image.src = this.result;
                preview.appendChild(image);
            }, false);
            reader.readAsDataURL(file);
        }
    }
    document.querySelector('#gambarktp').addEventListener("change", previewgambarktp);

    function previewgambarnpwp() {
        var preview = document.querySelector('#previewnpwp');

        // Hapus semua elemen child di dalam elemen #preview
        while (preview.firstChild) {
            preview.removeChild(preview.firstChild);
        }

        if (this.files) {
            [].forEach.call(this.files, readAndPreview);
        }

        function readAndPreview(file) {
            if (!/\.(jpe?g|png|jpg)$/i.test(file.name)) {
                alert("Hanya file gambar dengan ekstensi .jpeg, .jpg, .png, yang diperbolehkan.");
                document.querySelector('#gambarnpwp').value = '';
                return;
            }
            var reader = new FileReader();
            reader.addEventListener("load", function () {
                var image = new Image();
                image.width = 150;
                image.height = 150;
                image.title = file.name;
                image.src = this.result;
                preview.appendChild(image);
            }, false);
            reader.readAsDataURL(file);
        }
    }
    document.querySelector('#gambarnpwp').addEventListener("change", previewgambarnpwp);
</script>
@endsection
