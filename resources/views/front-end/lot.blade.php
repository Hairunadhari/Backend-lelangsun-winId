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
        padding: 10px;
    }

    .bungkus-lot {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(18rem, 1fr));
        grid-gap: 20px; /* Spasi antara card */
        padding: 0 20px; /* Spasi kiri dan kanan dari container */
        justify-content: center;
    }
    .bungkus-lot img{
        height: 200px;
    }
    @media (max-width: 600px) {

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
        <div class="bungkus-lot">
            @foreach ($lot_item as $item)
            <div class="card">
                <img src="{{asset('storage/image/'.$item->barang_lelang->gambarlelang[0]->gambar)}}"  alt="...">
                <div class="card-body">
                  <h5 class="card-title">{{$item->barang_lelang->barang}}</h5>
                  <p class="card-text">Rp. {{number_format($item->harga_awal)}}</p>
                  <p class="card-text"><i class="fas fa-map-marker-alt"></i> {{$item->event_lelang->alamat}}</p>
                  <p class="card-text"><i class="fas fa-calendar-alt"></i> {{$item->tanggal}} WIB</p>
                </div>
              </div>
              @endforeach
        </div>
        
    </div>
</section>
@endsection
