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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


</head>
<style>
    body {
        background-image: url('/asset-lelang/mobil5.jpg');
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

    .login .btnx {
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

    .alert-success {
        background-color: #63ed7a;
    }

</style>

<body style="overflow-x: hidden;">
    <div class="login mb-5">
        <div class="sec-login">
            <div class="isian-login">
                <h1 class="text-center">Win Lelang</h1>
                <form method="POST" action="# enctype=" multipart/form-data"> @csrf <div class="card"
                    style="width: 20rem;">
                    <div class="card-body">
                        <input type="hidden" hidden value="{{$email}}" name="" id="email_user">
                        <p class="card-text">Terima kasih telah mendaftar! Kami baru saja mengirim link ke email
                            <b>{{$email}}</b>, Jika Anda tidak menerima link tersebut silahkan kirim ulang dengan
                            mengklik
                            link dibawah ini.</p>
                    </div>
            </div>
            <div class="btn btnx" id="kirimulangotp">Kirim Ulang Link Verifikasi</div>
            <div class="text-center text-primary" style="display:none" id="timer"> </div>
            </form>
        </div>
    </div>
    </div>
    <div class="last-text">
        <h5 class="text-center">2020-2021 BALAI LELANG SUN. DILINDUNGI HAK CIPTA</h5>
    </div>

    <script>
        $(document).ready(function () {
         
            let localStorageKey = 'countdownStartTime';
            let email_user = $('#email_user').val();

            // Function to start countdown
            function startCountdown(targetTime) {
                // Memperbarui timer setiap 1 detik
                let countdownInterval = setInterval(function () {
                    let now = new Date().getTime();
                    let timeDifference = targetTime - now;

                    // Menghitung menit dan detik
                    let seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

                    // Menampilkan timer di elemen dengan id "timer"
                    if (seconds >= 0) {
                        $('#timer').html(
                            `<button class="btn btn-light mt-3" disabled>Mengirim Ulang Verifikasi dalam ${seconds} detik</button>`
                        );
                    } else {
                        $('#timer').html(
                            `<button class="btn btn-light mt-3" disabled>Mengirim Ulang Verifikasi</button>`
                        );
                    }

                    // Jika waktu sudah habis, hentikan countdown dan hapus waktu awal dari localStorage
                    if (timeDifference <= 0) {
                        clearInterval(countdownInterval);
                        $('#timer').hide();
                        $('#kirimulangotp').show();
                        localStorage.removeItem(localStorageKey);
                    }
                }, 1000);
            }

            let statusOtp = localStorage.getItem("statusOtp");
            // Mengambil waktu awal dari localStorage
            let startTime = localStorage.getItem(localStorageKey);

            if (!statusOtp) {
                localStorage.setItem("statusOtp", true);


                // Jika tidak tersimpan, atur waktu awal saat ini dan simpan di localStorage
                if (!startTime) {
                    startTime = new Date().getTime();
                    localStorage.setItem(localStorageKey, startTime);
                    $('#timer').hide();
                } else {
                    // Menghitung waktu akhir (60 detik dari waktu awal)
                    let endTime = parseInt(startTime) + (60 * 1000);

                    // Memulai countdown timer
                    startCountdown(endTime);
                    $('#kirimulangotp').hide();
                    $('#timer').show();
                }
            }
            if (startTime) {
                // Menghitung waktu akhir (60 detik dari waktu awal)
                let endTime = parseInt(startTime) + (60 * 1000);

                // Memulai countdown timer
                startCountdown(endTime);
                $('#kirimulangotp').hide();
                $('#timer').show();
            }


            $(document).on('click', '#kirimulangotp', function (e) {
                // Sembunyikan tombol "Kirim Ulang Kode OTP"
                $('#kirimulangotp').hide();

                // Tampilkan timer
                $('#timer').show();
                startTime = new Date().getTime();
                localStorage.setItem(localStorageKey, startTime);

                // Hitung waktu akhir (60 detik dari waktu awal)
                let endTime = parseInt(startTime) + (60 * 1000);

                $.ajax({
                    method: 'get',
                    url: '/resend-link/'+email_user,
                    success: function (res) {
                        console.log('status =', res);
                        swal("Notifikasi", "Link Verifikasi Berhasil Terkirim, Silahkan Cek Ulang Email "+email_user, 'success', {});
                        startCountdown(endTime);
                    },
                    error: function (res) {
                        console.log('status =',res)
                        $('#timer').hide();
                        $('#kirimulangotp').show();
                        localStorage.removeItem(localStorageKey);
                        swal("Terjadi Kesalahan", "Gagal Mengirim Verifikasi Email", 'error', {});
                    }
                });
            });
        });

    </script>
    @if (Session::has('message'))
    <script>
        swal("SUCCESS", "{{Session::get('message')}}", 'success', {});

    </script>
    @endif
    @if (Session::has('warning'))
    <script>
        swal("Ada Kesalahan", "{{Session::get('warning')}}", 'warning', {});

    </script>
    @endif
    @if (Session::has('error'))
    <script>
        swal("Ups!", "{{Session::get('error')}}", 'error', {});

    </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

</html>
