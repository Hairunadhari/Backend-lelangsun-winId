@extends('front-end.layout')
@section('content')
<style>
    .con-kontak {
        background-image: url('asset-lelang/kontak1.png');
        height: 35vh;
        background-position: center;
        background-size: cover;
        width: 100%;
        padding: 50px;
        color: white;
        text-align: center;
    }
    .parent{
        padding: 50px;
        color: white;
        text-align: center;
        display: flex;
        justify-content: center;
    }
    .con-kontak2{
        background-image: url('asset-lelang/lelang2.jpg');
        height: auto;
        background-position: center;
        background-size: cover;
        width: 100%;
    }
    .card-kon, .card-kon2, .card-kon3{
        background-color: #31869b;
        padding: 40px 40px 10px 40px;
    }
    .card-kon2, .card-kon3{
        margin-left: 40px;
    }
    .a{
        width: 500px;
    }
    .lokasi{
        display: flex;
        justify-content: center;
    }
    .lokasi iframe{
        width: 100%;
        padding: 0px 100px 100px 100px;
    }
    @media (max-width: 600px) {
        .parent{
            padding: 20px;
            color: white;
            text-align: center;
            display: block;
        }
        .a{
            width: 200px;
        }
        .card-kon2, .card-kon3{
            margin-top: 10px;
            margin-left: 0px;
        }
        .lokasi iframe{
            width: 100%;
            padding: 20px;
        }
    }
</style>
    <section id="kontak">
        <div class="con-kontak">
            <h1>KONTAK</h1>
            <h4>Hubungi Kami Disini</h4>
        </div>
        <div class="con-kontak2">
            <div class="parent">
                <div class="card-kon">
                    <div class="a">
                        <h1><i class="fas fa-map-marker-alt"></i> </h1>
                        <h2>OUR ADDRESS</h2>
                        <h5>{{$data->alamat}}</h5>
                    </div>
                </div>
                <div class="card-kon2">
                    <h1><i class="fas fa-envelope"></i></h1> 
                    <h2>EMAIL US</h2>
                    <h5>{{$data->email}}</h5>
                </div>
                <div class="card-kon3">
                    <h1><i class="fas fa-phone-alt"></i></h1>
                    <h5>{{$data->no_telp}}</h5>
                </div>
            </div>
            <div class="lokasi">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.286256878279!2d106.71530589999999!3d-6.2259376!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69fb62354445c3%3A0x1c1460e2d8676d6a!2sBalai%20Lelang%20SUN!5e0!3m2!1sid!2sid!4v1694357265893!5m2!1sid!2sid" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>

    </section>
@endsection