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
            background-image: url('asset-lelang/mobile-lelang.jpg');
            height: 30vh;
            background-position: left;
            background-size: cover;
            background-repeat: no-repeat;
            width: 100%;
            padding: 20px;
        }

        #satu h1,
        #satu h4 {
            display: none;
        }

        #dua {
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
{{-- <section id="dua">
    <div class="lelang">
        <h3>Jadwal Lelang</h3>
        @foreach ($event as $item)
        <div class="event-lelangs mb-1">
            <img src="{{ asset('storage/image/'.$item->gambar) }}"  alt="...">
            <h3>{{$item->judul}}</h3>
            <h5><i class="fas fa-map-marker-alt"></i> {{$item->alamat}}</h5>
            <h5><i class="fas fa-calendar-alt"></i> {{$item->waktu}}</h5>
            @if (Auth::guard('peserta')->user())
                @if (!empty($item->lot_item[0]->id))
                    @php
                    $hashid = Crypt::encrypt($item->id)
                    @endphp
                    <a href="{{ route('user-bidding', ['id' => $hashid, 'lot' => $item->lot_item[0]->id]) }}"
                        class="btn btn-danger"><span>Masuk</span></a>
                @endif
            @endif
        </div>
        <div class="card" style="width: 18rem;">
            <img src="..." class="card-img-top" alt="...">
            <div class="card-body">
              <h5 class="card-title">Card title</h5>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
              <a href="#" class="btn btn-primary">Go somewhere</a>
            </div>
          </div>
        @endforeach
    </div>
</section> --}}
<section id="dua">
    <div class="lelang">
        <h3 class="text-center">Jadwal Lelang</h3>
        <div class="bungkus-lot">
            @foreach ($event as $item)
            {{-- @dd($item->lot_item) --}}
            <div class="card">
                <img src="{{ asset('storage/image/'.$item->gambar) }}"  alt="...">
                <div class="card-body">
                  <h5 class="card-title">{{$item->judul}}</h5>
                  <p class="card-text"><i class="fas fa-map-marker-alt"></i> {{$item->alamat}}</p>
                  <p class="card-text"><i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($item->waktu)->format('Y M d, H:i:s') }} WIB</p>
                  @php
                      $hashid = Crypt::encrypt($item->id)
                  @endphp
                  <div style="display: flex; gap: 1rem">
                      <a href="{{ route('user-bidding', ['id' => $hashid, 'lot' => $item->lot_item[0]->id ?? null]) }}"
                        class="btn btn-danger w-50 "><span>Masuk</span></a>
                        <a href="{{ route('user-view-monitor', ['id' => $hashid, 'lot' => $item->lot_item[0]->id ?? null]) }}"
                            class="btn btn-success w-50"><span>View Monitor</span></a>
                        </div>
                </div>
              </div>
              @endforeach
        </div>
    </div>
</section>


@endsection
