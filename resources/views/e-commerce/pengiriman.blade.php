@extends('app.layouts')
@section('content')
<style>
    .nav-pills {
        display: flex;
        gap: 5px;
    }

    .countdata {
        background-color: rgba(255, 255, 255, 0.25);
    }
    .btn-progress{
        background-size: 22px !important;
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
                    <div class="w-25 mb-3">
                            @csrf
                            <label for="">Filter Label Pengiriman</label>
                            <div style="display: flex; gap: 10px">

                                <input type="date" name="date" class="form-control" id="date">
                                <button id="dLabel"  class="btn btn-sm btn-danger"><i class="fas fa-print"></i> Download Label</button>
                                    <button class="btn btn-secondary" id="loading" style="display: none">Sedang Mendownload...</button>
                            </div>
                    </div>
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
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var table = $('#tablepesanan').DataTable({
            processing: true,
            ordering: false,
            serverSide: true,
            ajax: {
                url: '{{ url()->current() }}',
                data: function (data) {
                    data.date = $('#date').val()
                }
            },
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
                        data: "nama_order",
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
                        data: 'qty',
                        
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
                            if (data == 'confirmed') {
                                info = 'Pesanan telah dikonfirmasi. Menemukan pengemudi terdekat untuk dijemput.';
                            } else if(data == 'allocated') {
                                info='Kurir telah dialokasikan. Menunggu untuk mengambil.';
                            }else if (data == 'pickingUp') {
                                info='Kurir sedang dalam perjalanan untuk mengambil barang.';
                            }else if (data == 'picked') {
                                info='Barang telah diambil dan siap dikirim.';
                            }else if (data == 'droppingOff') {
                                info='Item sedang dalam perjalanan ke pelanggan.';
                            }else if (data == 'returnInTransit') {
                                info='Pesanan sedang dalam perjalanan kembali ke asal.';
                            }else if (data == 'onHold') {
                                info='Pengiriman Anda sedang ditahan saat ini. Kami akan mengirimkan barang Anda setelah diselesaikan.';
                            }else if (data == 'delivered') {
                                info='Barang telah dikirim.';
                            }else if (data == 'rejected') {
                                info='Pengiriman Anda ditolak. Silakan hubungi Biteship untuk informasi lebih lanjut.'
                            }else if (data == 'courierNotFound') {
                                info='Pengiriman Anda dibatalkan karena tidak ada kurir yang tersedia saat ini.'
                            }else if (data == 'returned') {
                                info='Pesanan berhasil dikembalikan.'
                            }else if (data == 'cancelled') {
                                info='Pesanan dibatalkan.'
                            }else if(data == 'disposed'){

                                info='Pesanan berhasil dibuang.'
                            }else{
                                info='Tidak Diketahui.'
                            }

                            if (data == null) {
                                a = '<span>-</span>';
                            } else {
                                a = `<button type="button" class="btn btn-outline-primary" data-toggle="tooltip" data-placement="top" title="${info}">${data}</button>`;
                            }
                            
                            return a;
                        }
                    },
                    {
                        data: null,
                        render: function (data) {

                            return `  <button type="button" data-toggle="tooltip" data-placement="top" title="detail pesanan" class="btn mb-1 btn-warning" data-toggle="modal" data-target="#exampleModal${data.id}">
                            <span class="text"><i class="fas fa-info"></i></span>
                        </button> <span><a href="/tracking" class="btn  btn-dark" data-toggle="tooltip" data-placement="top" title="lacak status pengiriman"><i class="fas fa-shipping-fast"></i></a></span>`;
                        }
                    }

            ],
        });

        $('#date').on('change', function () {
            var date = this.value;
            console.log('date', date);
            table.draw();

        });

        $(document).on('click', '#dLabel', function (e) {
        $(this).hide();
        $('#loading').show();
        let date = $('#date').val();
        $.ajax({
            method: 'post',
            url: '/download-pdf',
            data: {
                date: date,
            },
            success: function (res) {
                $('#dLabel').show();
                $('#loading').hide();
                window.open(res,'_blank');
                
            },
            error: function (res) {
                alert('Ada Kesalahan ',res);
            }
        });
    });

    });

</script>

@endsection

@section('modal')
<style>
    .idpengiriman p,
    .alamatasal p,
    .alamattujuan p {
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
                <h5 class="modal-title" id="exampleModalLabel">Detail Pengiriman </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
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
                                                @if ($item->order->pengiriman != null)
                                                    
                                                <p><b>{{$item->order->pengiriman->biteship_order_id}}</b></p>
                                                @else
                                                <p><b>-</b></p>
                                                    
                                                @endif

                                            </div>
                                            <div>

                                                <p>Resi Pengiriman</p>
                                                @if ($item->order->pengiriman != null)
                                                <p><b>{{$item->order->pengiriman->waybill_id}}</p></b>
                                                @else
                                                <p><b>-</b></p>
                                                    
                                                @endif
                                            </div>
                                            <div>
                                                <p>Kurir</p>
                                                {{-- @dd($item) --}}
                                                @if ($item->order == null)

                                                <p>-</p>
                                                @else
                                                <p><b>{{strtoupper($item->order->courier_company)}}
                                                        {{strtoupper($item->order->type) }}</b></p>
                                                @endif

                                            </div>
                                            <div>

                                                <p>Nama Driver</p>
                                                @if ($item->order->pengiriman != null)
                                                <p><b>{{$item->order->pengiriman->courier_name}}</p></b>
                                                @else
                                                <p><b>-</b></p>
                                                    
                                                @endif
                                            </div>
                                            <div>

                                                <p>Nomor Driver</p>
                                                @if ($item->order->pengiriman != null)
                                                <p><b>{{$item->order->pengiriman->courier_phone}}</p></b>
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
                                        <div style="display: flex; gap:5rem">
                                            <div>
                                                <p>Nama Pengirim</p>
                                                <p><b>{{$item->order->nama_pemilik_toko}}</b></p>

                                            </div>
                                            <div>
                                                <p>No Handphone</p>
                                                <p><b>{{$item->order->no_telephone_toko}}</b></p>

                                            </div>
                                        </div>
                                        <p>Alamat Detail</p>
                                        <p><b>{{$item->order->detail_alamat_toko}}</b></p>
                                        <div style="display: flex; gap:5rem">
                                            <div>
                                                <p>Asal Kecamatan</p>
                                                <p><b>{{$item->order->kecamatan_toko}}</b></p>
                                            </div>
                                            <div>
                                                <p>Kode Pos</p>
                                                <p><b>{{$item->order->postal_code_toko}}</b></p>
                                            </div>
                                            <div>

                                                <p>Asal Kota</p>
                                                <p><b>{{$item->order->kota_toko}}</b></p>
                                            </div>
                                            <div>
                                                <p>Asal Provinsi</p>
                                                <p><b>{{$item->order->provinsi_toko}}</b></p>
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
                                                @if ($item->order == null)
                                                <p></p>

                                                @else
                                                <p><b>{{$item->order->nama_user}}</b></p>

                                                @endif
                                            </div>
                                            <div>

                                                <p>No Handphone</p>
                                                @if ($item->order == null)
                                                <p></p>

                                                @else

                                                <p><b>{{$item->order->no_telephone_user}}</b></p>
                                                @endif
                                            </div>
                                        </div>
                                        <p>Alamat Detail</p>
                                        @if ($item->order == null)
                                        <p></p>

                                        @else
                                        <p><b>{{$item->order->detail_alamat_user}}</b></p>

                                        @endif
                                        <div style="display: flex; gap:5rem">
                                            <div>
                                                <p>Asal Kecamatan</p>
                                                @if ($item->order == null)
                                                <p></p>

                                                @else

                                                <p><b>{{$item->order->kecamatan_user}}</b></p>
                                                @endif
                                            </div>
                                            <div>
                                                <p>Kode Pos</p>
                                                @if ($item->order == null)

                                                <p>i</p>
                                                @else

                                                <p><b>{{$item->order->postal_code_user}}</b></p>
                                                @endif
                                            </div>
                                            <div>

                                                <p>Asal Kota</p>
                                                @if ($item->order == null)

                                                <p></p>
                                                @else

                                                <p><b>{{$item->order->kota_user}}</b></p>
                                                @endif
                                            </div>
                                            <div>
                                                <p>Asal Provinsi</p>
                                                @if ($item->order == null)
                                                <p></p>

                                                @else

                                                <p><b>{{$item->order->provinsi_user}}</b></p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p style="margin:0">Tanggal Dikirim</p>
                                    @if ($item->order->pengiriman != null)
                                    <p><b>{{$item->order->pengiriman->tanggal_dikirim}}</p></b>
                                    @else
                                    <p><b>-</b></p>
                                    @endif
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <p style="margin: 0">Harga Pengiriman</p>
                                    <p><b>Rp {{number_format($item->price,0,',','.')}}</b></p>
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
                                            <td class="text-center">{{$item->nama_produk}}</td>
                                            <td class="text-center">{{$item->qty}}</td>
                                            <td class="text-center">{{$item->berat_item}}</td>
                                        </tr>
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