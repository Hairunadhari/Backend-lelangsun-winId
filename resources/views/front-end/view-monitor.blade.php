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

    .scroll .card-body {
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
        width: 100%;
    }
    .rowzx{
        margin-top: 5rem;
        display:  flex;
        gap: 1rem;
    }
    .antrian-lot{
        height: max-content;

    }
    .lot-number{
        background-color: #DC3545;
        /* You can adjust the background color as needed */
        border: 1px solid #ccc;
        /* You can adjust the border as needed */
        border-radius: 10px;
        /* Makes it a circle */
        width: 60px;
        /* Adjust as needed */
        height: 40px;
         /* Adjust as needed */
         display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
        color: white;
        margin-bottom: 1rem;
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
            <div class="scroll">
                <div class="lot-number">Lot {{$lot_item[0]->no_lot}}</div>
                <div class="card card-bid">
                    <div class="card-header text-center p-3 bg-danger text-white border-bottom-0">
                        <p class="mb-0 fw-bold">Sistem Lelang</p>
                    </div>
                    <div class="card-body">
                        <div class="text-center" id="log-bid-user"></div>
                    </div>
                </div>
                <form action="#" method="post" id="message_form" onsubmit="return submitForm(this);">
                    <div class="mb-3">
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
 