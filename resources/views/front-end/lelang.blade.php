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
            <h3>Jadwal Lelang</h3>
            @foreach ($event as $item)
            <div class="event-lelangs mb-1">
                <h3>{{$item->judul}}</h3>
                <h5><i class="fas fa-map-marker-alt"></i> {{$item->alamat}}</h5>
                <h5><i class="fas fa-calendar-alt"></i> {{$item->waktu}}</h5>
                @php
                    $hashid = Crypt::encrypt($item->id)
                @endphp
                <a href="{{ route('user-bidding', ['id' => $hashid, 'lot' => $item->lot_item[0]->id]) }}" class="btn btn-danger"><span>Masuk</span></a>
            </div>
            @endforeach
        </div>
    </section>

@endsection