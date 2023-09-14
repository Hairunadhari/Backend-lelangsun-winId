@extends('front-end.layout')
@section('content')
<style>
    #satu {
        background-image: url('asset-lelang/event.jpg');
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
            color: white;
            padding: 20px;
        }
    #dua .bungkus-lot {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(18rem, 1fr));
        grid-gap: 20px; /* Spasi antara card */
        padding: 0 20px; /* Spasi kiri dan kanan dari container */
        justify-content: center;
    }
    #dua .bungkus-lot img{
        height: 200px;
    }
    @media (max-width: 600px) {
        #satu {
            background-image: url('asset-lelang/eventm.jpg');
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
            <h1>EVENTS</h1>
        <h4>Ayo ikuti event lelang secara langsung</h4>
        </div>
    </section>
    <section id="dua">
        <div class="lelang">
            <h3 class="text-center">Event List</h3>
            <div class="bungkus-lot">
                @foreach ($event as $item)
                <div class="card">
                    <img src="/asset-lelang/event-content.jpg"  alt="...">
                    <div class="card-body">
                      <h5 class="card-title">{{$item->judul}}</h5>
                      <p class="card-text"><i class="fas fa-map-marker-alt"></i> {{$item->alamat}}</p>
                      <p class="card-text"><i class="fas fa-calendar-alt"></i> {{$item->waktu}} WIB</p>
                      @php
                          $hashid = Crypt::encrypt($item->id)
                      @endphp
                      <a href="{{ route('detail-event-user', ['id' => $hashid]) }}" class="btn btn-primary">Detail Event</a>
                    </div>
                  </div>
                  @endforeach
            </div>
        </div>
    </section>

@endsection