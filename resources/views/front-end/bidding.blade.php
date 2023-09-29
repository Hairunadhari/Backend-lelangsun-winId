@extends('front-end.layout')
@section('content')
<style>
    #satu {
        background-image: url('/asset-lelang/detail_event.jpg');
        height: 100vh;
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
        grid-template-columns: repeat(auto-fill, minmax(15rem, 1fr));
        grid-gap: 0px; /* Spasi antara card */
        padding: 0px; /* Spasi kiri dan kanan dari container */
        justify-content: center;
    }
    #satu .bungkus-lot img{
        height: 200px;
    }
    .item-barang img{
        width: 200px;
        height: 200px;
    }
    .items img{
        box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin:5px; padding:0.25rem; border:1px solid #dee2e6; 
    }
    .scroll .card{
        height:300px;
        overflow: scroll;
        width: auto;
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
        <div class="row">
            <div class="card col-6">
                <h1>Event : {{$lot_item[0]->event_lelang->judul}}</h1>
                <img src="{{asset('storage/image/'.$lot_item[0]->barang_lelang->gambarlelang[0]->gambar)}}" class="my-2"  alt="...">
            </div>
            <div class="scroll col-6">
                <div class="card" >
                    <div class="card-body">
                        <div class="" id="log-bid-user"></div>
                    </div>
                  </div>
                  @if ($npl->count() > 0) {{--apakah user punya npl event--}}
                  <form action="#" method="post" id="message_form" onsubmit="return submitForm(this);">
                    <div class="mb-3">
                        <input type="hidden" class="form-control" name="email" id="email_user" value="{{Auth::guard('peserta')->user()->email}}" readonly>
                        <input type="hidden" readonly name="event_lelang_id" id="event_lelang_id_user" value="{{$lot_item[0]->event_lelang->id}}" class="form-control">
                        <input type="hidden" readonly name="peserta_npl_id" id="peserta_npl_id_user" value="{{Auth::guard('peserta')->user()->id}}" class="form-control">
                        <input type="hidden" readonly name="npl_id" id="npl_id_user" value="{{$npl[0]->id}}" class="form-control">
                        <input type="hidden" readonly name="lot_id" id="lot_item_id_user" value="{{$lot_item[0]->id}}" class="form-control">
                        <input type="hidden" readonly name="harga_awal" id="harga_awal_user" value="{{ (!empty($lot_item) && !empty($lot_item[0]->bidding) && count($lot_item[0]->bidding) > 0) ? $lot_item[0]->bidding[0]->harga_bidding : $lot_item[0]->harga_awal }}" class="form-control">
                        <input type="hidden" readonly name="harga_bidding" id="harga_bidding_user" value="{{$lot_item[0]->event_lelang->kategori_barang->kelipatan_bidding}}" class="form-control">
                        @php
                            $event_id_crypt= Crypt::encrypt($lot_item[0]->event_lelang->id);
                        @endphp
                        <input type="hidden" readonly  id="id_event_crypt" value="{{$event_id_crypt}}" class="form-control">
                    </div>
                    
                </form>
                <button  class="btn btn-success w-100" id="user_send_bidding">Bidding</button>
                @endif
            </div>
        </div>
    </section>
<script>
   
</script>
@endsection