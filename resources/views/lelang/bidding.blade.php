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
                                <div class="" id="messages"></div>
                            </div>
                            <div class="card-footer ">
                                <div class="">
                                    <form action="#" method="post" id="message_form">
                                        <div class="input-group">
                                            <input type="hidden" name="email" id="email" value="{{Auth::user()->email}}" class="form-control">
                                            <input type="hidden" name="event_lelang_id" id="event_lelang_id" value="{{$id}}" class="form-control">
                                            <input type="hidden" name="peserta_npl_id" id="peserta_npl_id" value="3" class="form-control">
                                            <input type="hidden" name="lot_id" id="lot_id" value="5" class="form-control">
                                            <input type="hidden" name="npl_id" id="npl_id" value="12" class="form-control">
                                            <input type="hidden" name="harga_bidding" id="harga_bidding" value="5000000" class="form-control">
                                                <div class="button-center d-flex justify-content-center w-100">
                                                    <div class="button-bid" style="display: flex; justify-content: center; width:100%;">
                                                        <button type="submit" id="send_message" class="btn btn-success">
                                                            <i class="fas fa-gavel"></i> Bidding
                                                        </button>
                                                        <button type="submit" id="send_message" class="btn btn-danger ml-2">
                                                            <i class="fas fa-ban"></i> Stop Bidding
                                                        </button>
                                                    </div>
                                                </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($lot_item as $index => $item)
                            @if ($index > 0)
                                <div class="card col-4 m-1" style="box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; border:1px solid #dee2e6;">
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
<script>
    // $("#form-bidding").submit(function(e) {
    //     e.preventDefault();
    //     var formData = new FormData(this);
    //     $.ajax({
    //         type:'POST',
    //         url:"{{route('add-bidding')}}",
    //         headers: {
    //                     'X-CSRF-TOKEN': "{{ csrf_token() }}"
    //                 },
    //         data: formData,
    //         cache:false,
    //         contentType:false,
    //         processData:false,
    //         success: (data) => {
    //             console.log(data);
    //         },
    //         error: function(data){
    //             console.log(data);
    //         }
    //     });
    // }); 

</script>
@endsection
