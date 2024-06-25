
@extends('app.layouts')
@section('content')
<style>
    .idpengiriman p,
    .alamatasal p,
    .alamattujuan p {
        margin: 0;
    }

</style>
        <div class="card">
            <div class="card-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Pengiriman </h5>
            </div>
            <div class="card-body">
                <div class="invoice-print">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class=" p-4"
                                        style="box-shadow: rgba(0, 0, 0, 0.02) 0px 1px 3px 0px, rgba(27, 31, 35, 0.15) 0px 0px 0px 1px; border-radius: 5px">
                                        <div class="idpengiriman"
                                            style="display: flex; justify-content: space-evenly; text-align: center;">
                                            <div>
                                                <p>Order Id</p>
                                                @if ($data->order->pengiriman != null)
                                                    
                                                <p><b>{{$data->order->pengiriman->biteship_order_id}}</b></p>
                                                @else
                                                <p><b>-</b></p>
                                                    
                                                @endif

                                            </div>
                                            <div>

                                                <p>Resi Pengiriman</p>
                                                @if ($data->order->pengiriman != null)
                                                <p><b>{{$data->order->pengiriman->waybill_id}}</p></b>
                                                @else
                                                <p><b>-</b></p>
                                                    
                                                @endif
                                            </div>
                                            <div>
                                                <p>Kurir</p>
                                                {{-- @dd($data) --}}
                                                @if ($data->order == null)

                                                <p>-</p>
                                                @else
                                                <p><b>{{strtoupper($data->order->courier_company)}}
                                                        {{strtoupper($data->order->type) }}</b></p>
                                                @endif

                                            </div>
                                            <div>

                                                <p>Nama Driver</p>
                                                @if ($data->order->pengiriman != null)
                                                <p><b>{{$data->order->pengiriman->courier_name}}</p></b>
                                                @else
                                                <p><b>-</b></p>
                                                    
                                                @endif
                                            </div>
                                            <div>

                                                <p>Nomor Driver</p>
                                                @if ($data->order->pengiriman != null)
                                                <p><b>{{$data->order->pengiriman->courier_phone}}</p></b>
                                                @else
                                                <p><b>-</b></p>
                                                    
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="alamatasal p-4"
                                        style="box-shadow: rgba(0, 0, 0, 0.02) 0px 1px 3px 0px, rgba(27, 31, 35, 0.15) 0px 0px 0px 1px; border-radius: 5px">
                                        <h6>Alamat Asal</h6>
                                        @if ($data->order == null)
                                        <p>Nama Pengirim</p>
                                        <p>No Handphone</p>
                                        <p>Alamat Detail</p>
                                        <div style="display: flex; justify-content: space-evenly">
                                            <div>
                                                <p>Asal Kecamatan</p>
                                            </div>
                                            <div>
                                                <p>Kode Pos</p>
                                            </div>
                                            <div>

                                                <p>Asal Kota</p>
                                            </div>
                                            <div>
                                                <p>Asal Provinsi</p>
                                            </div>
                                        </div>
                                        @else
                                        <div style="display: flex; gap:5rem">
                                            <div>
                                                <p>Nama Pengirim</p>
                                                <p><b>{{$data->order->nama_pemilik_toko}}</b></p>

                                            </div>
                                            <div>
                                                <p>No Handphone</p>
                                                <p><b>{{$data->order->no_telephone_toko}}</b></p>

                                            </div>
                                        </div>
                                        <p>Alamat Detail</p>
                                        <p><b>{{$data->order->detail_alamat_toko}}</b></p>
                                        <div style="display: flex; gap:5rem">
                                            <div>
                                                <p>Asal Kecamatan</p>
                                                <p><b>{{$data->order->kecamatan_toko}}</b></p>
                                            </div>
                                            <div>
                                                <p>Kode Pos</p>
                                                <p><b>{{$data->order->postal_code_toko}}</b></p>
                                            </div>
                                            <div>

                                                <p>Asal Kota</p>
                                                <p><b>{{$data->order->kota_toko}}</b></p>
                                            </div>
                                            <div>
                                                <p>Asal Provinsi</p>
                                                <p><b>{{$data->order->provinsi_toko}}</b></p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="alamattujuan p-4"
                                        style="box-shadow: rgba(0, 0, 0, 0.02) 0px 1px 3px 0px, rgba(27, 31, 35, 0.15) 0px 0px 0px 1px; border-radius: 5px">
                                        <h6>Alamat Tujuan</h6>
                                        <div style="display: flex; gap:5rem">
                                            <div>
                                                <p>Nama Penerima</p>
                                                @if ($data->order == null)
                                                <p></p>

                                                @else
                                                <p><b>{{$data->order->nama_user}}</b></p>

                                                @endif
                                            </div>
                                            <div>

                                                <p>No Handphone</p>
                                                @if ($data->order == null)
                                                <p></p>

                                                @else

                                                <p><b>{{$data->order->no_telephone_user}}</b></p>
                                                @endif
                                            </div>
                                        </div>
                                        <p>Alamat Detail</p>
                                        @if ($data->order == null)
                                        <p></p>

                                        @else
                                        <p><b>{{$data->order->detail_alamat_user}}</b></p>

                                        @endif
                                        <div style="display: flex; gap:5rem">
                                            <div>
                                                <p>Asal Kecamatan</p>
                                                @if ($data->order == null)
                                                <p></p>

                                                @else

                                                <p><b>{{$data->order->kecamatan_user}}</b></p>
                                                @endif
                                            </div>
                                            <div>
                                                <p>Kode Pos</p>
                                                @if ($data->order == null)

                                                <p>i</p>
                                                @else

                                                <p><b>{{$data->order->postal_code_user}}</b></p>
                                                @endif
                                            </div>
                                            <div>

                                                <p>Asal Kota</p>
                                                @if ($data->order == null)

                                                <p></p>
                                                @else

                                                <p><b>{{$data->order->kota_user}}</b></p>
                                                @endif
                                            </div>
                                            <div>
                                                <p>Asal Provinsi</p>
                                                @if ($data->order == null)
                                                <p></p>

                                                @else

                                                <p><b>{{$data->order->provinsi_user}}</b></p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p style="margin:0">Tanggal Dikirim</p>
                                    @if ($data->order->pengiriman != null)
                                    <p><b>{{$data->order->pengiriman->tanggal_dikirim}}</p></b>
                                    @else
                                    <p><b>-</b></p>
                                    @endif
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <p style="margin: 0">Ongkos Kirim</p>
                                    <p><b>Rp {{number_format($data->order->pengiriman->insurance_amount,0,',','.')}}</b></p>
                                    <br>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-title mb-1">Barang</div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nama</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">Berat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">{{$data->nama_produk}}</td>
                                            <td class="text-center">{{$data->qty}}</td>
                                            <td class="text-center">{{$data->berat_item}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>
@endsection