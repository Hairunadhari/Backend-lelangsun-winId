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
        padding: 150px 150px 20px 150px;
    }

    .login .btn {
        width: 300px;
        margin-top: 20px;
        background-color: #ff0000;
        color: white
    }

    .no-akun {
        display: flex;
        justify-content: center;
        padding: 0 0 100px 0;
    }

    .no-akun a {
        color: #ff0000
    }
    .alert-success{
        background-color: #63ed7a;
    }
</style>

<body style="overflow-x: hidden;">
    <div class="login">
        <div class="sec-login">
            <div class="isian-login">
                <h1 class="text-center">Login Lelang</h1>
                @if (session('pesan'))
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <strong>{{ session('pesan') }}</strong>
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form method="POST" action="{{route('peserta.login')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="exampleInputPassword1">
                    </div>
                    <button type="submit" class="btn">Masuk</button>
                </form>
            </div>
        </div>
        <div class="no-akun">
            <h5>Belum Memiliki Akun?</h5>
            <a href="{{route('front-end-register')}}">Registrasi Disini</a>
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
