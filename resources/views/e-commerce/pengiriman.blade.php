@extends('app.layouts')
@section('content')
<style>
    .nav-pills {
        display: flex;
        gap: 1rem;
    }
    .countdata{
        background-color: rgba(255, 255, 255, 0.25);
    }
</style>
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="">Daftar Pengiriman</h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills mb-3 " id="myTab3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab"
                                aria-controls="home" aria-selected="true">Semua  <span class="badge countdata">{{$semua}}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="penjemputan-tab3" data-toggle="tab" href="#penjemputan3" role="tab"
                                aria-controls="penjemputan" aria-selected="false">Penjemputan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pengantaran-tab3" data-toggle="tab" href="#pengantaran3" role="tab"
                                aria-controls="pengantaran" aria-selected="false">Penjemputan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pengembalian-tab3" data-toggle="tab" href="#pengembalian3" role="tab"
                                aria-controls="pengembalian" aria-selected="false">pengembalian</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="ditahan-tab3" data-toggle="tab" href="#ditahan3" role="tab"
                                aria-controls="ditahan" aria-selected="false">ditahan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="selesai-tab3" data-toggle="tab" href="#selesai3" role="tab"
                                aria-controls="selesai" aria-selected="false">selesai</a>
                        </li>

                    </ul>
                    <div class="tab-content" id="myTabContent2">
                        <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="home-tab3">
                            <table class="table table-striped w-100" id="tablepesanan">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Order Id</th>
                                        <th>Nomor Resi</th>
                                        <th>Tgl Dibuat</th>
                                        <th>Nama Penerima</th>
                                        <th>Total Item</th>
                                        <th>Total Ongkir</th>
                                        <th>Status</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="penjemputan3" role="tabpanel" aria-labelledby="penjemputan-tab3">
                            <table class="table table-striped w-100" id="penjemputan">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Order Id</th>
                                        <th>Nomor Resi</th>
                                        <th>Tgl Dibuat</th>
                                        <th>Nama Penerima</th>
                                        <th>Total Item</th>
                                        <th>Total Ongkir</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="pengembalian3" role="tabpanel" aria-labelledby="pengembalian-tab3">
                            <table class="table table-striped w-100" id="pengembalian">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Order Id</th>
                                        <th>Nomor Resi</th>
                                        <th>Tgl Dibuat</th>
                                        <th>Nama Penerima</th>
                                        <th>Total Item</th>
                                        <th>Total Ongkir</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="ditahan3" role="tabpanel" aria-labelledby="ditahan-tab3">
                            <table class="table table-striped w-100" id="ditahan">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Order Id</th>
                                        <th>Nomor Resi</th>
                                        <th>Tgl Dibuat</th>
                                        <th>Nama Penerima</th>
                                        <th>Total Item</th>
                                        <th>Total Ongkir</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="selesai3" role="tabpanel" aria-labelledby="selesai-tab3">
                            <table class="table table-striped w-100" id="selesai">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Order Id</th>
                                        <th>Nomor Resi</th>
                                        <th>Tgl Dibuat</th>
                                        <th>Nama Penerima</th>
                                        <th>Total Item</th>
                                        <th>Total Ongkir</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#tablepesanan').DataTable({
            processing: true,
            ordering: false,
            serverSide: true,
            ajax: '{{ url()->current() }}',
            columns: [{
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "biteship_order_id",
                    render: function (data) {
                        if (data == null) {
                            a = '<span>-</span>';
                        } else {
                            a = '<span>' + data + '</span>';
                        }
                        return a;
                    }

                },
                {
                    data: "waybill_id",
                    render: function (data) {
                        if (data == null) {
                            a = '<span>-</span>';
                        } else {
                            a = '<span>' + data + '</span>';
                        }
                        return a;
                    }

                },
                {
                    data: "tanggal_dibuat",

                },
                {
                    data: "order.nama_user",
                    render: function (data) {
                        if (data == null) {
                            a = '<span>-</span>';
                        } else {
                            a = '<span>' + data + '</span>';
                        }
                        return a;
                    }
                },
                {
                    data: 'order',
                    render: function (data, row, type, meta) {
                        if (data == null) {
                            a = '<span>-</span>'
                        } else {

                            a = '<span>' + data.orderitem.length + '</span>';
                        }
                        return a;
                    }
                },
                {
                    data: "price",
                    render: function (data, type, row, meta) {
                        if (data == null) {
                            return '<span>-</span>';
                        } else {
                            if (type === 'display') {
                                // Mengubah data menjadi format mata uang dengan simbol IDR
                                return "Rp " + parseInt(data).toLocaleString('id-ID', {
                                    minimumFractionDigits: 0
                                });
                            } else {
                                // Untuk tipe data lain, kembalikan data aslinya
                                return data;
                            }
                        }
                    }
                },
                {
                    data: "status",
                    render: function (data) {
                        if (data == null) {
                            a = '<span>-</span>';
                        } else {
                            a = '<span>' + data + '</span>';
                        }
                        return a;
                    }
                },
                {
                    data: null,
                    render: function (data) {
                        
                        return `  <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exampleModal${data.id}">
                        <span class="text"><i class="far fa-edit"></i> Detail</span>
                    </button>`;
                    }
                }

            ],
        });
    });

</script>

@endsection
@section('modal')
<style>
    .idpengiriman p, .alamatasal p{
        margin: 0;
    }
</style>
@foreach ($dataModal as $item)
<!-- Modal -->
<div class="modal fade" id="exampleModal{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Pengiriman {{$item->id}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <div class="modal-body">
                    {{-- <div class="invoice"> --}}
                        <div class="invoice-print">
                            <div class="row">
                                <div class="col-lg-12">
                                   <div class="row mb-3">
                                    <div class="col-12">
                                        <div class=" p-4" style="box-shadow: rgba(0, 0, 0, 0.02) 0px 1px 3px 0px, rgba(27, 31, 35, 0.15) 0px 0px 0px 1px; border-radius: 5px">
                                            <div class="idpengiriman" style="display: flex; justify-content: space-evenly; text-align: center;" >
                                                <div>
                                                    <p >Order Id</p>
                                                    <p>{{$item->biteship_order_id}}</p>

                                                </div>
                                                <div>

                                                    <p>Resi Pengiriman</p>   
                                                    <p>{{$item->waybill_id}}</p>    
                                                </div>
                                                <div>
                                                    <p>Kurir</p>       
                                                    {{-- @dd($item) --}}
                                                    @if ($item->order == null)
                                                    
                                                    <p>-</p>
                                                    @else
                                                    <p>{{strtoupper($item->order->courier_company)}} {{strtoupper($item->order->type) }}</p>
                                                    @endif

                                                </div>
                                                <div>

                                                    <p>Nama Driver</p>   
                                                    <p>{{$item->courier_name}}</p>    
                                                </div>
                                                <div>

                                                    <p>Nomor Driver</p>   
                                                    <p>{{$item->courier_phone}}</p>    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                   </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div class="alamatasal p-4" style="box-shadow: rgba(0, 0, 0, 0.02) 0px 1px 3px 0px, rgba(27, 31, 35, 0.15) 0px 0px 0px 1px; border-radius: 5px">
                                          <h6>Alamat Asal</h6>
                                          @if ($item->order == null)
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
                                        <p>Nama Pengirim</p>
                                        <p><b>{{$item->order->nama_pemilik_toko}}</b></p>
                                        <p>No Handphone</p>
                                        <p>{{$item->order->no_telephone_toko}}</p>
                                        <p>Alamat Detail</p>
                                        <p>{{$item->order->detail_alamat_toko}}</p>
                                        <div style="display: flex; gap:5rem">
                                            <div>
                                                <p>Asal Kecamatan</p>
                                                <p>{{$item->kecamatan_toko}}</p>
                                            </div>
                                            <div>
                                                <p>Kode Pos</p>
                                                <p>{{$item->postal_code_toko}}</p>
                                            </div>
                                            <div>

                                                <p>Asal Kota</p>
                                                <p>{{$item->kota_toko}}</p>
                                            </div>
                                            <div>
                                                <p>Asal Provinsi</p>
                                                <p>{{$item->order->provinsi_toko}}</p>
                                            </div>
                                        </div>
                                        @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <div class="alamattujuan p-4" style="box-shadow: rgba(0, 0, 0, 0.02) 0px 1px 3px 0px, rgba(27, 31, 35, 0.15) 0px 0px 0px 1px; border-radius: 5px">
                                          <h6>Alamat Tujuan</h6>
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
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            <address>
                                                <strong>Tanggal Dikirim</strong>
                                                <br>
                                            </address>
                                        </div>
                                        <div class="col-md-6 text-md-right">
                                            <address>
                                                <strong>Harga Pengiriman</strong>
                                            </address>
                                        </div>
                                       
                                    </div>
                                </div>
                            </div>
                
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="section-title">Barang</div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Nama</th>
                                                    <th class="text-center">Quantity</th>
                                                    <th class="text-right">Berat</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- @foreach ($orderItems as $item)
                                                <tr>
                                                    <td>{{$item->nama_produk}}</td>
                                                    <td class="text-center">Rp {{number_format($item->harga,0,',','.')}}
                                                        @if ($item->promo_diskon != 0)
                                                        <span class="badge badge-sm badge-dark"><i class="fa fa-tag"></i> Diskon {{$item->promo_diskon}}%</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">{{$item->qty}}</td>
                                                    <td class="text-right">Rp {{number_format($item->total_harga_item,0,',','.')}}</td>
                                                </tr>
                                                @endforeach --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                {{-- </div> --}}
        </div>
    </div>
</div>
@endforeach

@endsection
