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
    .id-lot {
        position: absolute;
        top: -15px;
        /* Adjust as needed to make it slightly protrude */
        right: -15px;
        /* Adjust as needed to make it slightly protrude */
        padding: 5px;
        background-color: #DC3545;
        /* You can adjust the background color as needed */
        border: 1px solid #ccc;
        /* You can adjust the border as needed */
        border-radius: 50%;
        /* Makes it a circle */
        width: 60px;
        /* Adjust as needed */
        height: 60px;
        /* Adjust as needed */
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
        color: white;
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
        .item-barang{
            margin-top: 20px !important;
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
                    <img src="{{ asset('storage/image/'.$event->gambar) }}"  alt="..." class="mb-3">
                        <h5 class="card-title"><i class="fas fa-calendar-alt"></i> Event: {{$event->judul}}</h5>
                      <p class="card-text"><i class="fas fa-map-marker-alt"></i> Alamat: {{$event->alamat}}</p>
                      <p class="card-text"><i class="fas fa-map-marked-alt"></i> Link Lokasi: <a href="{{$event->link_lokasi}}"></a></p>
                      <p class="card-text"><i class="fas fa-clock"></i> Waktu Event: {{$event->waktu}} WIB</p>
                      <p><i class="fas fa-exclamation"></i> Keterangan :</p><textarea readonly class="card-text form-control">{{strip_tags($event->deskripsi)}}</textarea>
                  </div>
            </div>
            <h1>List Lot</h1>
            <div class="items">
                @php
                    $lot = 1;
                @endphp
                @foreach ($event->lot_item as $item)
                <div class="card item-barang m-2">
                    <div class="id-lot">Lot {{$item->no_lot}}</div>
                    @if (count($item->barang_lelang->gambarlelang) > 0)
                    <img src="{{asset('storage/image/'.$item->barang_lelang->gambarlelang[0]->gambar)}}"  alt="...">
                        
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{$item->barang_lelang->barang}}</h5>
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