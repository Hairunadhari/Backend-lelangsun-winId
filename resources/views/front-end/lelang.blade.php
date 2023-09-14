@extends('front-end.layout')
@section('content')
<style>
    #satu {
        background-image: url('asset-lelang/web-lelang.jpg');
        height: 100vh;
        background-position: left;
        background-size: cover;
        background-repeat: no-repeat;
        width: 100%;
        padding: 50px;
    }
    #dua{
            background-image: url('asset-lelang/lelang2.jpg');
            height: auto;
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            width: 100%;
            padding: 20px;
            color: white;
            display: flex;
            justify-content: center;
            text-align: center;
        }

    @media (max-width: 600px) {
        #satu {
            background-image: url('asset-lelang/mobile-lelang.jpg');
            height: 30vh;
            background-position: left;
            background-size: cover;
            background-repeat: no-repeat;
            width: 100%;
            padding: 20px;
        }
        #satu h1,#satu h4{
            display: none;
        }
        #dua{
            background-image: url('asset-lelang/lelang2.jpg');
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            width: 100%;
            padding: 20px;
            color: white;
            text-align: center
        }
    }
</style>
    <section id="satu">
        <div class="judul">
            <h1>LELANG</h1>
        <h4>Fast Bid</h4>
        </div>
    </section>
    <section id="dua">
        <div class="lelang">
            <h3>Lelang</h3>
        </div>
    </section>

@endsection