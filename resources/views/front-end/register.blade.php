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
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</head>
<style>
    body {
        background-image: url('asset-lelang/mobil5.jpg');
        height: 100vh;
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

    .warn {
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
                        <label class="form-label">Nama <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="nama" required>
                        @if ($errors->has('nama'))
                        <div class="warn"><small class="text-alert">eror!</small></div>
                        @endif

                    </div>
                    <div class="mb-1 col-6">
                        <label class="form-label">Email <span style="color: red">*</span></label>
                        <input type="email" class="form-control" name="email" required>
                        @if ($errors->has('email'))
                        <div class="warn"><small class="text-alert">Email Sudah Terdaftar!</small></div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="mb-1 col-6">
                        <label class="form-label">Telepon <span style="color: red">*</span></label>
                        <input type="number" class="form-control" name="no_telp" required>
                        @if ($errors->has('no_telp'))
                        <div class="warn"><small class="text-alert">eror!</small></div>
                        @endif
                    </div>
                    <div class="mb-1 col-6">
                        <label class="form-label">Nik</label>
                        <input type="text" class="form-control" name="nik">
                    </div>
                </div>
                <div class="row">
                    <div class="mb-1 col-6">
                        <label class="form-label">Npwp</label>
                        <input type="text" class="form-control" name="npwp">
                    </div>
                    <div class="mb-1 col-6">
                        <label class="form-label">No Rekening</label>
                        <input type="text" class="form-control" name="no_rek">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label for="" class="form-label">Foto KTP</label>
                        <input type="file" class="form-control" name="foto_ktp" accept=".jpg,.png,.jpeg" id="gambarktp">
                    </div>
                    <div class="col-6">
                        <label for="" class="form-label">Foto NPWP</label>
                        <input type="file" class="form-control" name="foto_npwp" accept=".jpg,.png,.jpeg" id="gambarnpwp">
                    </div>
                </div>
                <div class="mb-1">
                    <label class="form-label">Alamat <span style="color: red">*</span></label>
                    <textarea class="form-control" name="alamat" required></textarea>
                    @if ($errors->has('alamat'))
                    <div class="warn"><small class="text-alert">eror!</small></div>
                    @endif
                </div>
                <div class="mb-1">
                    <label class="form-label">Password <span style="color: red">*</span></label>
                    <input type="password" class="form-control" name="password" required>
                    @if ($errors->has('password'))
                    <div class="warn"><small class="text-alert">Password harus memiliki 5 karakter!</small></div>
                    @endif
                </div>
                <div class="mb-1">
                    <label class="form-label">Confirm Password <span style="color: red">*</span></label>
                    <input type="password" class="form-control" name="confirm_password" required>
                    @if ($errors->has('confirm_password'))
                    <div class="warn"><small class="text-alert">Konfirmasi Password Tidak Cocok!</small></div>
                    @endif
                </div>
                <button type="submit" class="btn btn-danger w-100 regis-button mb-2">Registrasi</button>
            </form>
        </div>
    </div>
    <div class="last-text">
        <h5 class="text-center">2020-2021 SUN BALAI LELANG. DILINDUNGI HAK CIPTA</h5>
    </div>

    <script>
        document.querySelector('#gambarktp').addEventListener("change", validateFilektp);

        function validateFilektp() {
            if (this.files && this.files.length > 0) {
                [].forEach.call(this.files, function(file) {
                    if (!/\.(jpe?g|png|jpg)$/i.test(file.name)) {
                        alert("Hanya file gambar dengan ekstensi .jpeg, .jpg, .png, yang diperbolehkan.");
                        document.querySelector('#gambarktp').value = '';
                    }
                });
            }
        }

        document.querySelector('#gambarnpwp').addEventListener("change", validateFilenpwp);

        function validateFilenpwp() {
            if (this.files && this.files.length > 0) {
                [].forEach.call(this.files, function(file) {
                    if (!/\.(jpe?g|png|jpg)$/i.test(file.name)) {
                        alert("Hanya file gambar dengan ekstensi .jpeg, .jpg, .png, yang diperbolehkan.");
                        document.querySelector('#gambarnpwp').value = '';
                    }
                });
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
</body>

</html>
