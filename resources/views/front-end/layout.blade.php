<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Win Lelang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
</head>
<style>
    .navbar-nav {
        margin-left: 400px;
    }

    .navbar-nav .nav-link {
        margin-left: 20px;
    }

    .logosun {
        margin-left: 150px;
        margin-top: 20px;
    }

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
        padding: 100px;
        display: flex;
        justify-content: center;
    }
    .testimoni-bungkus-card .card{
        margin-left: 20px;
        border: 1px solid white;
        padding: 10px
        
    }
    footer{
        background-color: #252525;
        color: white;
        width: 100%;
        height: auto;
    }
    #content-4 .con1{
        display: flex;
        justify-content: center;
    }
    .confooter{
        display: flex;
        justify-content: space-between;
        padding: 50px 50px 0 50px;
    }
    .card-footer{
        margin-inline: 100px;
        margin-bottom: 50px;
    }

    @media (max-width: 600px) {
        .navbar-nav {
            margin-left: 0;
        }

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
            font-size: 20px
        }

        #img-slider img {
            height: 200px;
        }

        #img-slider .carousel-inner {
            height: auto
        }

        #content-3 {
            background-image: url('/asset-lelang/mobile-02.png');
            background-size: cover;
            background-repeat: no-repeat;
            /* padding-top: 50px; */
            /* padding-left: 50px; */
            background-position: center;
            height: 52vh;
        }

        #content-3 .bungkus-img {
            display: none;
        }

        #content-3 .button-lelang {
            display: flex;
            justify-content: center;
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
            
        }
        #content-4 {
            padding-top: 50px;
        }
        .confooter{
            display: block;
            padding: 10px 10px 0 10px;
        }
        .card-footer{
            margin-inline: 10px;
            margin-bottom: 50px;
        }
        footer h4, p, li{
            font-size: 16px;
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
                    <a class="nav-link fw-semibold" aria-current="page" href="#">Beranda</a>
                    <a class="nav-link fw-semibold" href="#">Lot</a>
                    <a class="nav-link fw-semibold" href="#">Lelang</a>
                    <a class="nav-link fw-semibold">Events</a>
                    <a class="nav-link fw-semibold">Kontak</a>
                    <a class="nav-link fw-semibold">Login</a>
                </div>
            </div>
        </div>
    </nav>
    <section id="img-slider">
        <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('asset-lelang/car1.jpg') }}" class="d-block w-100" alt="...">
                    <div class="carousel-text">
                        <h1>Ikuti Lelang Dimana Saja</h1>
                        <p>Dengan Fitur Live Auction Dan Time Auction</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('asset-lelang/car2.jpg') }}" class="d-block w-100" alt="...">
                    <div class="carousel-text">
                        <h1>Ikuti Lelang Dimana Saja</h1>
                        <p>Dengan Fitur Live Auction Dan Time Auction</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('asset-lelang/car3.jpg') }}" class="d-block w-100" alt="...">
                    <div class="carousel-text">
                        <h1>Ikuti Lelang Dimana Saja</h1>
                        <p>Dengan Fitur Live Auction Dan Time Auction</p>
                    </div>
                </div>
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
        <div class="button-lelang">
            <button class="btn btn-danger">Ikuti Lelang</button>
        </div>
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
                <div class="card">
                    <img src="{{ asset('asset-lelang/profile_picture.png') }}" width="50" alt="">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora culpa hic, minus dolor laborum eos doloremque qui labore numquam! Voluptates.</p>
                    <hr>
                    <div class="cardcild">
                        <p>Ahmad</p>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                    </div>
                </div>
                <div class="card">
                    <img src="{{ asset('asset-lelang/profile_picture.png') }}" width="50" alt="">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora culpa hic, minus dolor laborum eos doloremque qui labore numquam! Voluptates.</p>
                    <hr>
                    <div class="cardcild">
                        <p>Ahmad</p>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                    </div>
                </div>
                <div class="card">
                    <img src="{{ asset('asset-lelang/profile_picture.png') }}" width="50" alt="">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora culpa hic, minus dolor laborum eos doloremque qui labore numquam! Voluptates.</p>
                    <hr>
                    <div class="cardcild">
                        <p>Ahmad</p>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                        <i class="fas fa-star" style="color: #f8fd08;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="confooter">
            <div class="card-footer">
                <h4>TENTANG KAMI</h4>
                <p>Tentang Kami</p>
                <i class="fab fa-instagram"></i>
                <i class="fab fa-facebook"></i>
                <i class="fab fa-twitter"></i>
                <i class="fab fa-youtube"></i>
            </div>
            <div class="card-footer">
                <h4>MENU</h4>
                <ul>
                    <li>Beranda</li>
                    <li>Lot</li>
                    <li>Lelang</li>
                    <li>Event</li>
                </ul>
            </div>
            <div class="card-footer">
                <h4>TENTANG </h4>
                <p><i class="fas fa-map-marker-alt"></i> Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi, excepturi.</p>
                <p><i class="fas fa-phone-alt"></i> +192819281</p>
                <p><i class="fas fa-envelope"></i> lelangsun@gmail.com</p>
            </div>
        </div>
        <h4 class="text-center">2020-2021 SUN BALAI LELANG. DILINDUNGI HAK CIPTA</h4>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
</body>

</html>
