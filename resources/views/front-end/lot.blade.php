@extends('front-end.layout')
@section('content')
<style>
    .lot {
        background-image: url('asset-lelang/lot1.jpg');
        height: 100vh;
        background-position: left;
        background-size: cover;
        background-repeat: no-repeat;
        width: 100%;
        padding: 20px;
    }

    .card {
        width: 300px;
        height: 400px;
        padding: 20px;
        background-color: #31869b;
        color: white
    }

    select {
        margin-top: 20px;
    }

    .judul {
        padding: 50px;
    }

    @media (max-width: 600px) {
        .card {
            width: 200px;
            height: 300px;
            padding: 10px;
            background-color: #31869b;
            color: white;
        }

        .card01 {
            display: flex;
            justify-content: center
        }

        .judul {
            padding: 10px;
        }

        .lot {
            background-image: url('asset-lelang/mobilelot.jpg');
            height: auto;
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            width: 100%;
            padding: 10px;
        }
    }

</style>
<section>
    <div class="lot">
        <h1>LOT</h1>
        <h3>{{ now()->format('d-M-Y') }}</h3>
        <div class="card01">
            <div class="card">
                <div class="judul">
                    <h4 class="text-center">FILTER</h4>
                </div>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="search...">
                </div>
                <form action="/action_page.php">
                    <select name="cars" id="cars">
                        <option value="volvo">Brand</option>
                        <option value="saab">Saab</option>
                        <option value="opel">Opel</option>
                        <option value="audi">Audi</option>
                    </select>
                    <br>
                    <select name="cars" id="cars">
                        <option value="volvo">Tahun</option>
                        <option value="saab">Saab</option>
                        <option value="opel">Opel</option>
                        <option value="audi">Audi</option>
                    </select>
                    <br>
                    <select name="cars" id="cars">
                        <option value="volvo">Harga Awal</option>
                        <option value="saab">Saab</option>
                        <option value="opel">Opel</option>
                        <option value="audi">Audi</option>
                    </select>
                </form>
            </div>
        </div>
        <div class="hasil-filter">
            
        </div>
    </div>
</section>
@endsection
