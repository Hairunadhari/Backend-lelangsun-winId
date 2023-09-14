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
        height: 89vh;
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
        padding: 50px 50px 20px 50px;
    }

    .login button {
        width: 300px;
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

</style>

<body style="overflow-x: hidden;">
    <div class="login">
        <div class="sec-login">
            <form method="POST" action="{{route('register-user')}}" enctype="multipart/form-data">
                @csrf
                <h1 class="text-center">Registrasi</h1>
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" class="form-control" name="nama" required>
                    
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                    @if ($errors->has('email'))
                    <div class="warn"><small class="text-alert">Email Sudah Terdaftar!</small></div>
                    @endif
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea class="form-control" name="alamat" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                    @if ($errors->has('password'))
                    <div class="warn"><small class="text-alert">Password harus memiliki 5 karakter!</small></div>
                    @endif
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="confirm_password" required>
                    @if ($errors->has('confirm_password'))
                    <div class="warn"><small class="text-alert">Konfirmasi Password Tidak Cocok!</small></div>
                    @endif
                </div>
                <button type="submit" class="btn btn-danger">Registrasi</button>
            </form>
        </div>
    </div>
    <div class="last-text">
        <h5 class="text-center">2020-2021 SUN BALAI LELANG. DILINDUNGI HAK CIPTA</h5>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
</body>

</html>
