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

    .my-2 {
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
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
    .rowx{
        display:  flex;
        gap: 2rem;
    }
    .col-6x{
        width: 50%;
    }
    .rowzx{
        margin-top: 5rem;
        display:  flex;
        gap: 1rem;
    }
    .antrian-lot{
        height: max-content;

    }
    @media (max-width: 600px) {
        #satu {
            /* background-image: url('/asset-lelang/detail_event.jpg'); */
            height: auto;
            background-position: left;
            background-size: cover;
            background-repeat: no-repeat;
            width: 100%;
            padding: 20px;
        }
        .rowx{
            display:  block;
            /* gap: 2rem; */
        }
        .col-6x{
            width: 100%;
            margin-bottom: 1rem;
        }
        .antrian-lot{
            width: 100%;
            margin-bottom: 1rem;
        }
        .rowzx{
            display: block;
        }
    }

</style>
<section id="satu">
    <div class="judul">
        <h4>EVENT : {{$lot_item[0]->event_lelang->judul}}</h4>
    </div>
    <div class="rowx">
        <div class="col-6x">
            <div class="card">
                <div class="id-lot">Lot {{$lot_item[0]->no_lot}}</div>
                @if ($lot_item[0]->barang_lelang->gambarlelang !== null)

                <img src="{{asset('storage/image/'.$lot_item[0]->barang_lelang->gambarlelang[0]->gambar)}}" class="my-4"
                    @endif alt="...">
                <div class="card-body">
                    <h5 class="card-title">{{ $lot_item[0]->barang_lelang->barang }}</h5>
                    <p class="card-text">{{$lot_item[0]->barang_lelang->brand }}</p>
                    <p class="card-text">Rp {{ number_format($lot_item[0]->harga_awal) }}</p>
                </div>
            </div>
        </div>
        <div class="col-6x">
            <div class="scroll">
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
                        <input type="hidden" readonly name="user_id" id="user_id_web" value="{{Auth::user()->id}}"
                            class="form-control">
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
                            value="{{$lot_item[0]->event_lelang->kategori_barang->kelipatan_bidding}}"
                            class="form-control">
                    </div>
                </form>
                @if ($npl->count() > 0) {{--apakah user punya npl event--}}
                <input type="hidden" readonly name="npl_id" id="npl_id_user" value="{{$npl[0]->id}}"
                    class="form-control">
                <button class="btn btn-success w-100" id="user-send-bidding" style="display:none">Bidding</button>
                <div id="loading" style="background-color: #191d21; box-shadow: 0 2px 6px #728394; display: none;"
                    class="p-1 text-center">
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
        </div>

        
    </div>
    <div class="rowzx ">
        @foreach ($lot_item as $index => $item)
        @if ($index > 0)
        <div class="card  antrian-lot"
            style="box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; border:1px solid #dee2e6; ">
            <div class="id-lot">Lot {{$item->no_lot}}</div>
            @if (count($item->barang_lelang->gambarlelang) > 0)
            <img src="{{ asset('storage/image/' . $item->barang_lelang->gambarlelang[0]->gambar) }}"
                class="card-img-top" alt="..." style=" width:auto; height: 400px;">
            @endif
            <div class="card-body">
                <h5 class="card-title">{{ $item->barang_lelang->barang }}</h5>
                <p class="card-text">{{ $item->barang_lelang->brand }}</p>
                <p class="card-text">Rp {{ number_format($item->harga_awal) }}</p>
            </div>
        </div>
        @endif
        @endforeach

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
                    var harga = value.harga_bidding; // Angka yang ingin diformat
                    var hargaFormatted = harga.toLocaleString('id-ID', {
                        currency: 'IDR'
                    });
                    $('#log-bid-user').prepend(
                        '<div class="mb-3 px-3 py-2" style="background-color: green; color: white; border-radius: 10px"><h5 class="mb-0">' +
                        value.email + ' ' + ': Rp ' + hargaFormatted + '</h5></div>');
                });
            }
        });
    });

</script>
@endsection
