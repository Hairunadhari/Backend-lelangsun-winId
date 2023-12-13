@extends('front-end.layout')
@section('content')
<style>
    #satu {
        background-image: url('detail_event.jpg');
        height: auto;
        background-position: left;
        background-size: cover;
        background-repeat: no-repeat;
        width: 100%;
        padding: 50px;
        color: white;
    }
   
    #satu .bungkus-lot {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(18rem, 1fr));
        grid-gap: 20px; /* Spasi antara card */
        padding: 0 20px; /* Spasi kiri dan kanan dari container */
        justify-content: center;
    }
    #satu .items {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(13rem, 1fr));
        grid-gap: 0px; /* Spasi antara card */
        padding: 0px; /* Spasi kiri dan kanan dari container */
        justify-content: center;
    }
    #satu .bungkus-lot img{
        height: 200px;
    }
    .item-barang img{
        /* width: 200px; */
        height: 200px;
    }
    .items img{
        /* box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin:5px; padding:0.25rem; border:1px solid #dee2e6;  */

    }
    @media (max-width: 600px) {
        #satu {
            background-image: url('detail_event.jpg');
            height: auto;
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
            <h1> DETAIL EVENTS</h1>
        </div>
        <div class="lelang">
            <div class="bungkus-lot">
                <div class="card p-2">
                    <img src="{{ asset('storage/image/'.$event->gambar) }}"  alt="...">
                        <h5 class="card-title">{{$event->judul}}</h5>
                      <p class="card-text"><i class="fas fa-map-marker-alt"></i> {{$event->alamat}}</p>
                      <p class="card-text"><i class="fas fa-calendar-alt"></i> {{$event->tanggal}} WIB</p>
                  </div>
            </div>
            <h1>List Lot</h1>
            <div class="items">
                @foreach ($event->lot_item as $item)
                <div class="card item-barang m-2">
                    <img src="{{asset('storage/image/'.$item->barang_lelang->gambarlelang[0]->gambar)}}"  alt="...">
                    <div class="card-body">
                        <h5 class="card-title">{{$item->barang_lelang->barang}}</h5>
                        <h5 class="card-title">{{$item->barang_lelang->brand}}</h5>
                        <h5 class="card-title">{{$item->barang_lelang->tahun_produksi}}</h5>
                        @php
                        $hashid = Crypt::encrypt($item->id)
                    @endphp
                        <a href="{{url('/detail-lot', $hashid)}}"
                            class="btn btn-danger w-100"><span>Detail Lot</span></a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection