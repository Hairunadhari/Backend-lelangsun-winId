<!doctype html>
<html lang="en">

<head>
    @inject('setting', 'App\Models\Setting')
        @php
            $data = $setting::where('status', 'active')->first();
        @endphp
    <meta charset="utf-8">
    <meta name="description" content="{{$data->deskripsi}}">
    <meta name="keywords" content="tes">
    <meta name="author" content="John Doe">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Win Lelang</title>
    <link rel="icon" href="{{ asset('asset-lelang/logo.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="page" data-page="user">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css"
        integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    @vite(['resources/css/app.css' , 'resources/js/app.js'])

</head>
<style>
    body{
        margin: 0;
    }
    .navbar-nav {
        margin-left: 300px;
    }

    .navbar-nav .nav-link {
        margin-left: 20px;
    }

    .logosun {
        margin-left: 150px;
        margin-top: 20px;
    }


    footer {
        background-color: #252525;
        color: white;
        width: 100%;
        height: auto;
    }

    #content-4 .con1 {
        display: flex;
        justify-content: center;
    }

    .confooter {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        padding: 50px 50px 0 50px;
    }

    .card-footer {
        margin-inline: 100px;
        margin-bottom: 50px;
    }

    .lonceng {
        position: absolute; 
        top: 0; 
        right: -10px; 
        background-color: red; 
        color: white; 
        border-radius: 50%; 
        padding: 4px 7px; 
        font-size: 12px;
    }

    @media (max-width: 600px) {
        .container-fluid .logosun {
            margin-left: 0;
            width: 100px;
        }

        .navbar-nav {
            margin-left: 0;
        }

        .confooter {
            display: block;
            padding: 10px 10px 0 10px;
        }

        .card-footer {
            margin-inline: 10px;
            margin-bottom: 30px;
        }

        footer h5,
        p,
        li {
            font-size: 16px;
        }
        footer{
            text-align: center;
        }
        .navbar-nav{
            text-align: center;
        }
        .lonceng{
            top: 0; 
            right: 100px; 
        }
    }

</style>

<body style="overflow-x: hidden;">
    <nav class="navbar navbar-expand-lg bg-white">
        <div class="container-fluid">
            <img class="logosun" src="{{ asset('asset-lelang/logo.png') }}" alt="">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    {{-- @if (Auth::user()) --}}
                    <a class="nav-link fw-semibold" aria-current="page" href="{{route('beranda')}}">Beranda</a>
                    {{-- <a class="nav-link fw-semibold" href="{{route('front-end-lot')}}">Lot</a> --}}
                    <a class="nav-link fw-semibold" href="{{route('front-end-lelang')}}">Lelang</a>
                    <a class="nav-link fw-semibold" href="{{route('front-end-event')}}">Events</a>
                    <a class="nav-link fw-semibold" href="{{route('front-end-kontak')}}">Kontak</a>
                    @if (Auth::user())
                        {{-- ambil model notifikasi --}}
                        @inject('notifikasi', 'App\Models\Notifikasi')
                        @php
                            $id = Auth::user()->id;
                            $total_pesan = $notifikasi::where('user_id', $id)->where('is_read','belum dibaca')->count();
                        @endphp
                        <a class="nav-link fw-semibold" href="{{route('front-end-notif')}}" style="position: relative;">
                            <i class="fas fa-bell"></i>
                            <span class="lonceng"
                                style="">{{$total_pesan}}</span>
                        </a>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fas fa-user"></i> {{Auth::user()->name}}
                            </a>
                            <ul class="dropdown-menu">
                                <form method="POST" action="{{ route('peserta.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item has-icon text-danger"
                                        style="cursor: pointer">
                                        <i class="fas fa-sign-out-alt mt-2"></i> <span style="font-size: 14px">Logout</span>
                                    </button>
                                </form>
                            </ul>
                        </li>
                    @else
                    <a class="nav-link fw-semibold" href="{{route('front-end-login')}}">Login</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>
    @yield('content')
    <footer>
        
        <div class="confooter">
            <div class="card-footer ">
                <h4>TENTANG KAMI</h4>
                <p>Tentang Kami</p>
                <a href="{{$data->ig}}" class="text-white" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                <a href="{{$data->fb}}" class="text-white" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook"></i></a>
                <a href="{{$data->twitter}}" class="text-white" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a>
                <a href="{{$data->yt}}" class="text-white" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a>
            </div>
            <div class="card-footer">
                <h4>MENU</h4>
               
                <div>Beranda</div>
                <div>Lelang</div>
                <div>Events</div>
                <div>Kontak</div>
            </div>
            <div class="card-footer">
                <h4>TENTANG </h4>
                <p><i class="fas fa-map-marker-alt"></i> {{$data->alamat}}</p>
                <p><i class="fas fa-phone-alt"></i> {{$data->no_telp}}</p>
                <p><i class="fas fa-envelope"></i> {{$data->email}}</p>
            </div>
        </div>
        <div class="last-text">
            <h5 class="text-center">2020-2021 SUN BALAI LELANG. DILINDUNGI HAK CIPTA</h5>
        </div>
    </footer>
    @if (Session::has('message'))
        <script>
            swal("SUCCESS","{{Session::get('message')}}",'success',{
            });
        </script>
    @endif
    @if (Session::has('error'))
        <script>
            swal("Upsss","{{Session::get('error')}}",'error',{
            });
        </script>
    @endif
    @if (Session::has('warning'))
        <script>
            swal("Upsss","{{Session::get('warning')}}",'warning',{
            });
        </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
    <script src ="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
      </script>
</body>

</html>
