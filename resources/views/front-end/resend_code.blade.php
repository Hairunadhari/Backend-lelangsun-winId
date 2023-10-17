<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Win Lelang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css"
        integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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

    .verify {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

</style>

<body style="overflow-x: hidden;">
    <div class="verify">
        <div class="card" style="width: 24rem;">
            @php
            $email = $user->email;
            list($username, $domain) = explode('@', $email);
            $hiddenEmail = substr($username, 0, 3) . str_repeat('*', max(strlen($username) - 3, 0)) . '@' . $domain;
            @endphp
            <div class="card-body">
                <form action="#" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{$user->email}}" name="email" id="email">
                    <input type="hidden" value="{{$user->id}}" name="id" id="id">
                    <p class="card-text">Terima kasih telah mendaftar! Kami baru saja mengirim link ke email
                        {{$hiddenEmail}}, Jika Anda tidak menerima email tersebut silahkan kirim ulang dengan mengklik
                        link dibawah ini.</p>
                    </form>
                    <button id="send-email" class="btn btn-primary">Resend Verification Email</button>
            </div>

        </div>
    </div>
    <h5 class="">2020-2021 SUN BALAI LELANG. DILINDUNGI HAK CIPTA</h5>

    <script>
    $(document).ready(function() {
        let countdown = localStorage.getItem('countdown');

        if (countdown && countdown > 0) {
            startCountdown(countdown);
            $('#send-email').prop('disabled', true); // Matikan tombol jika countdown sedang berjalan
            $('#send-email').removeClass('btn-primary');
            $('#send-email').addClass('btn-secondary');
        }

        $(document).on('click', '#send-email', function (e) {
            e.preventDefault();
            let email = $('#email').val();
            let id = $('#id').val();
            let button = $(this);

            if (!button.prop('disabled')) {
                button.removeClass('btn-primary');
                button.addClass('btn-secondary');
                button.prop('disabled', true);

                startCountdown(60);

                $.ajax({
                    method: 'post',
                    url: '/resend-email',
                    data: {
                        id: id,
                        email: email,
                    },
                    success: function (res) {
                        console.log(res);
                    }
                });
            }
        });

        function startCountdown(seconds) {
            let button = $('#send-email');
            let interval = setInterval(function() {
                button.text('Kirim ulang dalam ' + seconds + ' detik');
                seconds--;

                if (seconds < 0) {
                    clearInterval(interval);
                    button.prop('disabled', false);
                    button.text('Resend Verification Email');
                    button.addClass('btn-primary');
                    button.removeClass('btn-secondary');
                    localStorage.removeItem('countdown');
                } else {
                    localStorage.setItem('countdown', seconds);
                }
            }, 1000);
        }
    });


    </script>
    <script src ="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
    <script>
        $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
      </script>
</body>

</html>
