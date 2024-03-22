@extends('app.layouts')
@section('content')
<style>
     .id-lot {
        position: absolute;
        top: -15px; /* Adjust as needed to make it slightly protrude */
        right: -15px; /* Adjust as needed to make it slightly protrude */
        padding: 5px;
        background-color: #DC3545; /* You can adjust the background color as needed */
        border: 1px solid #ccc; /* You can adjust the border as needed */
        border-radius: 50%; /* Makes it a circle */
        width: 50px; /* Adjust as needed */
        height: 50px; /* Adjust as needed */
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
        color: white;
    }
     .lot-first {
        background-color: #DC3545; /* You can adjust the background color as needed */
        border-radius: 10px; /* Makes it a circle */
        width: max-content; 
        /* height: 30px;  */
        text-align: center;
        color: white;
        padding: .5rem;
    }
</style>
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card p-3">
                <div class="row">
                    <div class="col-6 col-md-6 col-lg-6 ">
                        <div class="card"
                            style="box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; border:1px solid #dee2e6;">
                            <div class="card-header">
                                <h4>Detail Barang Lelang</h4>
                            </div>
                            @if ($lot_item[0]->barang_lelang->gambarlelang !== null)
                            <img src="{{asset('storage/image/'.$lot_item[0]->barang_lelang->gambarlelang[0]->gambar)}}"
                                alt="..." style="width:auto; ">
                            @endif
                            @if ($lot_item[0]->barang_lelang->kategoribarang_id == 1 || $lot_item[0]->barang_lelang->kategoribarang_id == 2)
                                
                            <div class="card-body">
                                <h3 class="card-title">{{$lot_item[0]->barang_lelang->barang}}</h3>
                                <p class="card-text fw-bold">BRAND : {{$lot_item[0]->barang_lelang->brand}}</p>
                                <p class="card-text fw-bold">TAHUN : {{$lot_item[0]->barang_lelang->tahun_produksi}}</p>
                                <p class="card-text fw-bold">NO POLISI : {{$lot_item[0]->barang_lelang->no_polisi}}</p>
                                <p class="card-text fw-bold">GRADE : {{$lot_item[0]->barang_lelang->grade_utama}}</p>
                            </div>
                            @else
                                
                            <div class="card-body">
                                <h3 class="card-title">{{$lot_item[0]->barang_lelang->barang}}</h3>
                                <p class="card-text fw-bold">BRAND : {{$lot_item[0]->barang_lelang->brand}}</p>
                                <p class="card-text fw-bold">Deskripsi : {!!$lot_item[0]->barang_lelang->keterangan!!}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-6 col-md-6 col-lg-6">
                        <div class="lot-judul p-2">
                            <div class="lot-first mb-1">

                                <h5 class="mb-0">LOT {{$lot_item[0]->no_lot}}</h5>
                            </div>
                            <h4>Harga Awal : Rp {{number_format($lot_item[0]->harga_awal,0,',','.')}}</h4>
                        </div>
                        <div class="card chat-box" id="mychatbox"
                            style="box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; border:1px solid #dee2e6;">
                            <div class="card-header">
                                <h4>Sistem Lelang</h4>
                            </div>
                            <div class="card-body chat-content">
                                <div class="" id="log-bid"></div>
                            </div>
                            <div class="card-footer ">
                                <div class="">
                                    <form action="#" method="post" id="message_form"
                                        onsubmit="return submitForm(this);">
                                        <div class="input-group">
                                            <input type="hidden" name="email" id="email" value="BidOffline"
                                                class="form-control">
                                            @php
                                            $event_id_crypt= Crypt::encrypt($event_id);
                                            @endphp
                                            <input type="hidden" name="event_lelang_id" id="event_lelang_id"
                                                value="{{$event_id}}" class="form-control">
                                            <input type="hidden" name="event_lelang_id" id="event_lelang_id_web_user"
                                                value="{{$event_id}}" class="form-control">
                                            <input type="hidden" name="event_lelang_id_crypt" id="event_lelang_id_crypt"
                                                value="{{$event_id_crypt}}" class="form-control">
                                            <input type="hidden" name="lot_id" id="lot_item_id"
                                                value="{{$lot_item[0]->id}}" class="form-control">
                                            <input type="hidden" name="harga_awal" id="harga_awal"
                                                value="{{ (!empty($lot_item) && !empty($lot_item[0]->bidding) && count($lot_item[0]->bidding) > 0) ? $lot_item[0]->bidding[0]->harga_bidding : $lot_item[0]->harga_awal }}"
                                                class="form-control">
                                            <input type="hidden" name="kelipatan_bid" id="kelipatan_bid"
                                                value="{{$lot_item[0]->event_lelang->kategori_barang->kelipatan_bidding}}"
                                                class="form-control">
                                            <input type="hidden" readonly id="status_bid_lot_admin"
                                                value="{{$lot_item[0]->status_bid}}"
                                                class="form-control">
                                                <div class="text-center button-center w-100 mb-2">
                                                    <span class="badge badge-secondary text-center">
                                                        <div id="timer" data-seconds="<?= $setting->waktu_bid ?>">
                                                            00:{{$setting->waktu_bid}}</div>
                                                    </span>
                                                </div>
                                            <div id="con-bid" class="button-center w-100" style="display:none">
                                                <div class="button-bid"
                                                    style="display: flex; justify-content: center; width:100%;">
                                                    <button type="submit" id="send_bidding" class="btn btn-success">
                                                        <i class="fas fa-gavel"></i> Bidding
                                                    </button>
                                                    <button type="submit" id="stop-bidding" class="btn btn-danger ml-2">
                                                        <i class="fas fa-ban"></i> Stop Bidding
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="text-center button-center w-100" id="loading" style="display: none;">
                                                <div class="buttons">
                                                    <a href="#" class="btn disabled btn-dark btn-progress">Progress</a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <button id="start-bid" class="btn btn-success w-100 my-2">
                                        <h6 class="mb-0"><i class="fas fa-play"></i> Start Bid</h6>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($lot_item as $index => $item)
                            @if ($index > 0)
                            <div class="card mx-3 p-1"
                                style="box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; border:1px solid #dee2e6; width: 160px">
                                <div class="id-lot">Lot {{$item->no_lot}}</div>
                                @if (count($item->barang_lelang->gambarlelang) > 0)
                                <img src="{{ asset('storage/image/' . $item->barang_lelang->gambarlelang[0]->gambar) }}"
                                    class="card-img-top" alt="..." style="height:100px; width:auto;">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->barang_lelang->barang }}</h5>
                                    <p class="card-text">{{ $item->barang_lelang->brand }}</p>
                                    <p class="card-text">Rp {{ $item->harga_awal }}</p>
                                </div>
                            </div>
                            @endif
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection