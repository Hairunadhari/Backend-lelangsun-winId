@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card p-3">
                <div class="row">
                    <div class="col-6 col-md-6 col-lg-6 " >
                        <div class="card" style="box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; border:1px solid #dee2e6;">
                            <div class="card-header">
                                <h4>Detail Barang Lelang</h4>
                            </div>
                            <img src="{{asset('storage/image/'.$lot_item[0]->barang_lelang->gambarlelang[0]->gambar)}}" alt="..." style="width:auto; ">
                            <div class="card-body">
                                <h1 class="card-title">{{$lot_item[0]->barang_lelang->barang}}</h1>
                                <h5 class="card-text">BRAND : {{$lot_item[0]->barang_lelang->brand}}</h5>
                                <h5 class="card-text">TAHUN : {{$lot_item[0]->barang_lelang->tahun_produksi}}</h5>
                                <h5 class="card-text">NO POLISI : {{$lot_item[0]->barang_lelang->no_polisi}}</h5>
                                <h5 class="card-text">GRADE : {{$lot_item[0]->barang_lelang->grade}}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-6 col-lg-6">
                        <div class="lot-judul" style="padding-top: 20px; padding-left: 20px;">
                            <h4>LOT 00{{$lot_item[0]->id}}</h4>
                        <h4>Rp {{$lot_item[0]->harga_awal}}</h4>
                        </div>
                        <div class="card chat-box" id="mychatbox" style="box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; border:1px solid #dee2e6;">
                            <div class="card-header">
                                <h4>Sistem Lelang</h4>
                            </div>
                            <div class="card-body chat-content">
                                <div class="" id="log-bid"></div>
                            </div>
                            <div class="card-footer ">
                                <div class="">
                                    <form action="#" method="post" id="message_form" onsubmit="return submitForm(this);">
                                        <div class="input-group">
                                            <input type="hidden" name="email" id="email" value="BidOffline" class="form-control">
                                            @php
                                                $event_id_crypt= Crypt::encrypt($event_id);
                                            @endphp
                                            <input type="hidden" name="event_lelang_id" id="event_lelang_id" value="{{$event_id}}" class="form-control">
                                            <input type="hidden" name="event_lelang_id_crypt" id="event_lelang_id_crypt" value="{{$event_id_crypt}}" class="form-control">
                                            <input type="hidden" name="lot_id" id="lot_item_id" value="{{$lot_item[0]->id}}" class="form-control">
                                            <input type="hidden" name="harga_awal" id="harga_awal" value="{{ (!empty($lot_item) && !empty($lot_item[0]->bidding) && count($lot_item[0]->bidding) > 0) ? $lot_item[0]->bidding[0]->harga_bidding : $lot_item[0]->harga_awal }}" class="form-control">
                                            <input type="hidden" name="harga_bidding" id="harga_bidding" value="{{$lot_item[0]->event_lelang->kategori_barang->kelipatan_bidding}}" class="form-control">
                                            {{-- <input type="text" id="role" value="{{Auth::user()->role->role}}" class="form-control"> --}}
                                                <div id="con-bid" class="button-center w-100" style="display:none">
                                                    <div class="d-flex justify-content-center mb-2">
                                                        <span class="badge badge-secondary text-center">
                                                            <div id="timer" data-seconds="<?= $setting->waktu_bid ?>">00:{{$setting->waktu_bid}}</div>
                                                        </span>
                                                    </div>
                                                    <div class="button-bid" style="display: flex; justify-content: center; width:100%;">
                                                        <button type="submit" id="send_bidding" class="btn btn-success">
                                                            <i class="fas fa-gavel"></i> Bidding
                                                        </button>
                                                        <button type="submit" id="stop-bidding" class="btn btn-danger ml-2">
                                                            <i class="fas fa-ban"></i> Stop Bidding
                                                        </button>
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
                                <div class="card col-4 m-1 p-1" style="box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; border:1px solid #dee2e6;">
                                    <img src="{{ asset('storage/image/' . $item->barang_lelang->gambarlelang[0]->gambar) }}" class="card-img-top" alt="..." style="height:100px; width:auto;">
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
