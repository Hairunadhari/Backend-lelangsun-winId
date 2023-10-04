<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Win Lelang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css"
        integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<style>
    body {
        background-image: url('asset-lelang/mobil5.jpg');
        height: auto;
        background-position: center;
        background-repeat: no-repeat;
        color: white;
    }

    body::after {
        content: "";
        background-color: rgba(0, 0, 0, 0.5);
        /* Ubah opasitas sesuai kebutuhan */
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        /* Membuat lapisan berada di belakang gambar */
    }

    .sec-login {
        display: flex;
        justify-content: center;
        /* padding: 20px 20px 20px 20px; */
    }

    .login button {
        width: 600px;
        margin-top: 20px;
        color: white
    }
    .warn{
        background-color: #ff0000;
        margin-top: 3px;
        width: auto;
        padding: 3px;
        border-radius: 5px; 
    }
    /* .regis-button{
        margin-bottom: 100px;
    } */
    /* .copyrgiht{
        margin-bottom: 100px;
    } */

</style>

<body style="overflow-x: hidden;">
    <div class="login">
        <div class="sec-login">
            <form method="POST" action="{{route('register-user')}}" enctype="multipart/form-data">
                @csrf
                <h1 class="text-center">Registrasi</h1>
                <div class="row">
                    <div class="mb-1 col-6">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control" name="nama" required>
                        
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                        @if ($errors->has('email'))
                        <div class="warn"><small class="text-alert">Email Sudah Terdaftar!</small></div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="mb-1 col-6">
                        <label class="form-label">Telepon</label>
                        <input type="text" class="form-control" name="no_hp" required>
                        @if ($errors->has('email'))
                        <div class="warn"><small class="text-alert">Email Sudah Terdaftar!</small></div>
                        @endif
                    </div>
                    <div class="mb-1 col-6">
                        <label class="form-label">Nik</label>
                        <input type="text" class="form-control" name="nik" required>
                        @if ($errors->has('email'))
                        <div class="warn"><small class="text-alert">Email Sudah Terdaftar!</small></div>
                        @endif
                    </div>
                </div>
                <div class="mb-1">
                    <label class="form-label">Npwp</label>
                    <input type="text" class="form-control" name="npwp" required>
                    @if ($errors->has('email'))
                    <div class="warn"><small class="text-alert">Email Sudah Terdaftar!</small></div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-6">
                        <label for="" class="form-label">Foto KTP</label>
                        <input type="file" class="form-control" name="foto_ktp" id="gambarktp">
                        <div id="previewktp" class="mt-3"></div>
                    </div>
                    <div class="col-6">
                        <label for="" class="form-label">Foto NPWP</label>
                        <input type="file" class="form-control" name="foto_npwp" id="gambarnpwp">
                        <div id="previewnpwp" class="mt-3"></div>
                    </div>
                </div>
                <div class="mb-1">
                    <label class="form-label">Alamat</label>
                    <textarea class="form-control" name="alamat" required></textarea>
                </div>
                <div class="mb-1">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                    @if ($errors->has('password'))
                    <div class="warn"><small class="text-alert">Password harus memiliki 5 karakter!</small></div>
                    @endif
                </div>
                <div class="mb-2">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="confirm_password" required>
                    @if ($errors->has('confirm_password'))
                    <div class="warn"><small class="text-alert">Konfirmasi Password Tidak Cocok!</small></div>
                    @endif
                </div>
                <button type="submit" class="btn btn-danger w-100 regis-button mb-5">Registrasi</button>
            </form>
        </div>
    </div>
    <div class="last-text">
        <h5 class="text-center">2020-2021 SUN BALAI LELANG. DILINDUNGI HAK CIPTA</h5>
    </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
</body>

</html>
