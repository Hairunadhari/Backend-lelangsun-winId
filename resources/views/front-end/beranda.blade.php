@extends('front-end.layout')
@section('content')
<style>
     .carousel-inner {
        height: 400px;
    }

    .carousel-text {
        position: absolute;
        top: 100px;
        color: white;
        left: 150px
    }

    #content-3 {
        width: 100%;
        height: 149vh;
        background-image: url('/asset-lelang/web-01.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: left;
        padding-top: 150px;
        padding-left: 50px;
    }
    #content-4 {
        width: 100%;
        height: auto;
        background-image: url('/asset-lelang/dark-01.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: left;
        padding-top: 150px;
    }

    .card-4 .anakan-card .card {
        background-color: #000000c7;
        color: white;
        text-align: center;
        border: 2px solid white;
    }

    .card-4 .anakan-card .card img {
        width: 100px;
    }

    .card-4 {
        padding: 50px;
        display: flex;
        justify-content: center;
    }

    .anakan-card {
        margin-right: 30px;
    }

    .anakan-card .card {
        padding: 10px 10px 20px 10px;
    }

    .why-sun {
        color: white;
        display: flex;
        justify-content: center;
        padding: 50px;
        border: 1px solid white;
        background-color: #000000c7;

    }

    .why-sun .isian img {
        width: 100px;
        border-radius: 50px
    }

    .why-sun .isian {
        text-align: center;
        padding: 20px;
    }
    .con3{
        padding: 50px
    }
    #content5{
        background-image: url('/asset-lelang/dark-01.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        height: auto;
        width: 100%;
        padding-top: 50px;
    }
    .testimoni-bungkus-card{
        padding: 50px;
        display: flex;
        justify-content: center;
    }
    .testimoni-bungkus-card .card{
        margin-left: 20px;
        border: 1px solid white;
        padding: 10px;
        width: 30vw;
        
    }
    

    @media (max-width: 600px){
        .container-fluid .logosun {
            margin-left: 0;
            width: 100px;
        }

        .carousel-text {
            position: absolute;
            top: 60px;
            color: white;
            left: 50px
        }

        .carousel-text h1 {
            font-size: 26px
        }

        #img-slider img {
            height: 200px;
        }

        #img-slider .carousel-inner {
            height: auto
        }

        #content-3 {
            background-image: url('/asset-lelang/Mobile-02.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            
            height: 52vh;
        }

        #content-3 .bungkus-img {
            display: none;
        }

        #content-3 .button-lelang {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
            padding: 80px 50px 0 0;
        }

        .anakan {
            text-align: center;
            width: 200px;
        }
        .card-4 {
            display: block;
            padding: 10px;
        }
        .card-4 .anakan-card .card img {
            width: 70px;
        }
        .anakan-card {
            padding: 10px 10px 10px 10px;
            margin-right: 0px;
        }
        .why-sun {
            color: white;
            display: block;
            padding: 0px
        }
        .testimoni-bungkus-card{
            padding: 20px;
            display: block;
        }
        .testimoni-bungkus-card .card{
            margin-left: 0px;
            border: 1px solid white;
            padding: 10px;
            margin-bottom: 20px;
            width: 100%; 
            
        }
        #content-4 {
            padding-top: 50px;
        }
    }

    @media only screen and (min-width: 601px) and (max-width: 992px){
        
    #content-3 {
        width: 100%;
        height: 149vh;
        background-image: url('/asset-lelang/web-01.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }
    }
</style>
<section id="img-slider">
    <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach ($banner as $key => $item)
            <div class="carousel-item {{$key == 0 ? 'active' : ''}}">
                <img src="{{ asset('storage/image/'.$item->gambar) }}" class="d-block w-100" alt="...">
                <div class="carousel-text">
                    <h1>{{$data->judul}}</h1>
                    <p>{{$data->deskripsi}}</p>
                </div>
            </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

<section id="content-3">
    <div class="bungkus-img row">
        <div class="con1 col-7">
            <span style="font-size: 20px">SUN BALAI LELANG!</span>
            <hr class="w-50">
            <h2>SUN BALAI LELANG MOBIL TERPERCAYA</h2>
            <br>
            <span>PT Balai Lelang SUN menyediakan opsi metode lelang dengan berbagai keunggulan masing-masing
                Onsite
                Auction, Live Auction, dan Online Auction. Yang dapat mendukung kebutuhan anda dalam mengikuti
                Event
                Lelang.
            </span>
            <br>
            <br>
        </div>
        <div class="con2 col-5">
            <h3>Etios Valco 2018</h3>
            <span>Harga awal <span class="fw-bold">200</span> jutaan <br> dilelang <br> jadi <span
                    class="fw-bold">150</span> jutaan</span>
        </div>
    </div>
    {{-- <div class="button-lelang">
        <a class="btn btn-danger" href="/user-lelang">Ikuti Lelang</a>
    </div> --}}
</section>

<section id="content-4">
    <div class="con1">
        <div class="isi">
            <div class="anakan text-white">
                <h1 class="text-center">CARA IKUT LELANG DI</h1>
                <br>
                <div style="">
                    <span>PT Balai Lelang SUN menyediakan opsi metode lelang dengan
                        berbagai keunggulan yang dapat mendukung kebutuhan anda dalam mengikuti Event Lelang</span>
                    <br>
                    <br>
                    <div class="youtub">
                        <h3>VIDEO TATA CARA LELANG</h3>
                        <br>
                        <iframe style="width: 100%;  border:3px solid #dee2e6; border-radius: 20px" 
                            src="https://www.youtube.com/embed/DINwhKAs51w?si=M1wh2XgZKHnGVcr1"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-4">
        <div class="anakan-card">
            <div class="card" >
                <div class="card-body">
                    <img src="{{ asset('asset-lelang/profile_picture.png') }}" alt="">
                    <br>
                    <br>
                    <h2 class="card-title">Onsite Auction</h2>
                    <p  class="card-text">Peserta dapat langsung melakukan penawaran atau
                        bidding di lokasi
                        balai lelang SUN sesuai dengan jadwal yang telah ditentukan.</p>
                </div>
            </div>
        </div>
        <div class="anakan-card">
            <div class="card" >
                <div class="card-body">
                    <img src="{{ asset('asset-lelang/profile_picture.png') }}" alt="">
                    <br>
                    <br>
                    <h2 class="card-title">Live Auction</h2>
                    <p  class="card-text">Peserta dapat mengikuti lelang melalui Website
                        sesuai
                        dengan jadwal yang telah ditentukan, tanpa harus datang ke lokasi lelang.</p>
                </div>
            </div>
        </div>
        <div class="anakan-card">
            <div class="card" >
                <div class="card-body">
                    <img src="{{ asset('asset-lelang/profile_picture.png') }}" alt="">
                    <br>
                    <br>
                    <h2 class="card-title">Timed Auction</h2>
                    <p  class="card-text">Peserta dapat mengikuti lelang online dimana saja,
                        hanya
                        dengan melalui website Balai Lelang SUN sesuai rentang waktu yang sudah ditentukan.</p>
                </div>
            </div>
        </div>
    </div>
    <h1 class="text-white text-center">KENAPA HARUS LELANG SUN?</h1>
    <div class="con3">
        <div class="why-sun">
            <div class="isian">
                <img src="{{ asset('asset-lelang/dark-06.jpg') }}" alt="">
                <h2>Lelang Secara Online & offline</h2>
                <span>Semua bisa menang lelang tanpa perlu hadir di lokasi melalui fitur Live Auction dan Timed Auction
                    atau
                    bisa langsung datang ke loasi untuk mengikuti lelang secara offline/floor</span>
            </div>
            <div class="isian">
                <img src="{{ asset('asset-lelang/dark-03.jpg') }}" alt="">
                <h2>Transaksi Aman &
                    Mudah</h2>
                <span>Pembayaran transaksi di Balai Lelang SUN lebih aman dan mudah karena pembayaran hanya melalui
                    transfer
                    ke Rekening Balai Lelang SUN</span>
            </div>
            <div class="isian">
                <img src="{{ asset('asset-lelang/dark-04.jpg') }}" alt="">
                <h2>Info Unit
                    Transparan</h2>
                <span>Balai Lelang SUN akan memberikan informasi terkini kendaraan yang akan dilelang secara lengkap dan
                    transparan</span>
            </div>
            <div class="isian">
                <img src="{{ asset('asset-lelang/dark-05.jpg') }}" alt="">
                <h2>Jadwal Lelang Rutin</h2>
                <span>Kami akan memberikan informasi secara rutin untuk jadwal lelang mingguan</span>
            </div>
        </div>
    </div>
</section>

<section id="content5">
    <div class="testimoni">
        <h1 class="text-white text-center">TESTIMONIAL</h1>
        <div class="testimoni-bungkus-card">
            @foreach ($ulasan as $item)
            <div class="card">
                <img src="{{ asset('asset-lelang/profile_picture.png') }}" width="50" alt="">
                <p>{{$item->ulasan}}</p>
                <hr>
                <div class="cardcild">
                    <p>{{$item->nama}}</p>
                    @switch($item->bintang)
                        @case(1)
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                            @break
                        @case(2)
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                            @break
                        @case(3)
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                            @break
                        @case(4)
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                            @break
                        @case(5)
                            
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                            @break
                    @endswitch
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection