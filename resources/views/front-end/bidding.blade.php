@extends('front-end.layout')
@section('content')
<style>
    #satu {
        background-image: url('/asset-lelang/detail_event.jpg');
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
        grid-gap: 20px;
        /* Spasi antara card */
        padding: 0 20px;
        /* Spasi kiri dan kanan dari container */
        justify-content: center;
    }

    #satu .items {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(15rem, 1fr));
        grid-gap: 0px;
        /* Spasi antara card */
        padding: 0px;
        /* Spasi kiri dan kanan dari container */
        justify-content: center;
    }

    #satu .bungkus-lot img {
        height: 200px;
    }

    .item-barang img {
        width: 200px;
        height: 200px;
    }

    .items img {
        box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px;
        margin: 5px;
        padding: 0.25rem;
        border: 1px solid #dee2e6;
    }

    .scroll .card {
        height: 500px;
        overflow: scroll;
        width: auto;
    }
    img{
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
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
        <h1>EVENT : {{$lot_item[0]->event_lelang->judul}}</h1>
    </div>
    <div class="row">
        <div class="card col-6">
            <h3>Lot : {{$lot_item[0]->id}}</h3>
            <img src="{{asset('storage/image/'.$lot_item[0]->barang_lelang->gambarlelang[0]->gambar)}}" class="my-2"
                alt="...">
                <div class="card-body">
                    <h5 class="card-title">{{ $lot_item[0]->barang_lelang->barang }}</h5>
                    <p class="card-text">{{$lot_item[0]->barang_lelang->brand }}</p>
                    <p class="card-text">Rp {{ number_format($lot_item[0]->harga_awal) }}</p>
                </div>
        </div>
        <div class="scroll col-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-center" id="log-bid-user"></div>
                </div>
            </div>
            <form action="#" method="post" id="message_form" onsubmit="return submitForm(this);">
                <div class="mb-3">
                    @if (Auth::user()) {{--apakah user terautentikasi--}}
                    <input type="hidden" class="form-control" name="email" id="email_user"
                        value="{{Auth::user()->email}}" readonly>
                        <input type="hidden" readonly name="user_id" id="user_id_web"
                            value="{{Auth::user()->id}}" class="form-control">
                    @endif
                    <input type="hidden" readonly name="event_lelang_id" id="event_lelang_id_user"
                        value="{{$lot_item[0]->event_lelang->id}}" class="form-control">
                    <input type="hidden" readonly name="event_lelang_id" id="event_lelang_id_web_user"
                        value="{{$lot_item[0]->event_lelang->id}}" class="form-control">
                        <input type="hidden" readonly name="lot_id" id="lot_item_id_user" value="{{$lot_item[0]->id}}"
                        class="form-control">
                        <input type="hidden" readonly name="harga_awal" id="harga_awal_user"
                        value="{{ (!empty($lot_item) && !empty($lot_item[0]->bidding) && count($lot_item[0]->bidding) > 0) ? $lot_item[0]->bidding[0]->harga_bidding : $lot_item[0]->harga_awal }}"
                        class="form-control">
                        <input type="hidden" readonly name="kelipatan_bid_user" id="kelipatan_bid_user"
                        value="{{$lot_item[0]->event_lelang->kategori_barang->kelipatan_bidding}}" class="form-control">
                    </div>
                    
                </form>
            @if ($npl->count() > 0) {{--apakah user punya npl event--}}
            <input type="hidden" readonly name="npl_id" id="npl_id_user" value="{{$npl[0]->id}}"
                    class="form-control">
            <button class="btn btn-success w-100" id="user-send-bidding" style="display:none">Bidding</button>
            <div id="loading" style="background-color: #191d21; box-shadow: 0 2px 6px #728394; display: none;" class="p-1 text-center">
                <div class="spinner-border " role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
           
            @endif
           
            @php
            $event_id_crypt= Crypt::encrypt($lot_item[0]->event_lelang->id);
            @endphp
            <input type="hidden" readonly name="id_event_crypt" id="id_event_crypt" value="{{$event_id_crypt}}"
                class="form-control">
        </div>
        <div class="row">
            @foreach ($lot_item as $index => $item)
            @if ($index > 0)
            <div class="card col-3 m-1 p-1"
                style="box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; border:1px solid #dee2e6;">
                <img src="{{ asset('storage/image/' . $item->barang_lelang->gambarlelang[0]->gambar) }}"
                    class="card-img-top" alt="..." style=" width:auto;">
                <div class="card-body">
                    <h5 class="card-title">{{ $item->barang_lelang->barang }}</h5>
                    <p class="card-text">{{ $item->barang_lelang->brand }}</p>
                    <p class="card-text">Rp {{ number_format($item->harga_awal) }}</p>
                </div>
            </div>
            @endif
            @endforeach

        </div>
        
    </div>
</section>
<script>
    $(document).ready(function () {
        let event_lelang_id = $('#event_lelang_id_user').val();
        let lot_item_id = $('#lot_item_id_user').val();
        $.ajax({
            method: 'post',
            url: '/log-bidding-user',
            data: {
                event_lelang_id: event_lelang_id,
                lot_item_id: lot_item_id,
            },
            success: function (res) {
                console.log(res);
                $.each(res, function (key, value) {
                    $('#log-bid-user').prepend(
                        '<div class="mb-3 px-3 py-2" style="background-color: green; color: white; border-radius: 10px"><h5 class="mb-0">' +
                        value.email + ' ' + ': ' + value.harga_bidding + '</h5></div>');
                });
            }
        });
    });

</script>
@endsection
