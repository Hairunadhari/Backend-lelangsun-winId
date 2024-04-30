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
                                aria-controls="home" aria-selected="true">Semua <span
                                    class="badge countdata">{{$semua}}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="penjemputan-tab3" data-toggle="tab" href="#penjemputan3" role="tab"
                                aria-controls="penjemputan" aria-selected="false">Penjemputan <span
                                    class="badge countdata">{{$pickingUp}}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pengantaran-tab3" data-toggle="tab" href="#pengantaran3" role="tab"
                                aria-controls="pengantaran" aria-selected="false">Penjemputan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pengantaran-tab3" data-toggle="tab" href="#pengantaran3" role="tab"
                                aria-controls="pengantaran" aria-selected="false">Pengantaran <span
                                class="badge countdata">{{$droppingOff}}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pengembalian-tab3" data-toggle="tab" href="#pengembalian3"
                                role="tab" aria-controls="pengembalian" aria-selected="false">Pengembalian <span
                                class="badge countdata">{{$returned}}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="ditahan-tab3" data-toggle="tab" href="#ditahan3" role="tab"
                                aria-controls="ditahan" aria-selected="false">Ditahan <span
                                class="badge countdata">{{$onHold}}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="selesai-tab3" data-toggle="tab" href="#selesai3" role="tab"
                                aria-controls="selesai" aria-selected="false">Selesai <span
                                class="badge countdata">{{$selesai}}</span></a>
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
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="pengantaran3" role="tabpanel"
                            aria-labelledby="pengantaran-tab3">
                            <table class="table table-striped w-100" id="pengantaran">
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
                        <div class="tab-pane fade" id="pengembalian3" role="tabpanel"
                            aria-labelledby="pengembalian-tab3">
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

        $('#penjemputan').DataTable({
            processing: true,
            ordering: false,
            serverSide: true,
            ajax: {
                url: '{{ url()->current() }}',
                data: function (data) {
                    data.status = 'pickingUp';
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

        $('#pengantaran').DataTable({
            processing: true,
            ordering: false,
            serverSide: true,
            ajax: {
                url: '{{ url()->current() }}',
                data: function (data) {
                    data.status = 'droppingOff';
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
        $('#pengembalian').DataTable({
            processing: true,
            ordering: false,
            serverSide: true,
            ajax: {
                url: '{{ url()->current() }}',
                data: function (data) {
                    data.status = 'returned';
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
        $('#ditahan').DataTable({
            processing: true,
            ordering: false,
            serverSide: true,
            ajax: {
                url: '{{ url()->current() }}',
                data: function (data) {
                    data.status = 'onHold';
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
        $('#selesai').DataTable({
            processing: true,
            ordering: false,
            serverSide: true,
            ajax: {
                url: '{{ url()->current() }}',
                data: function (data) {
                    data.status = 'selesai';
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
                {{-- <div class="invoice"> --}}
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
                                                <p><b>{{$item->biteship_order_id}}</b></p>

                                            </div>
                                            <div>

                                                <p>Resi Pengiriman</p>
                                                <p><b>{{$item->waybill_id}}</p></b>
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
                                                <p><b>{{$item->courier_name}}</p></b>
                                            </div>
                                            <div>

                                                <p>Nomor Driver</p>
                                                <p><b>{{$item->courier_phone}}</p></b>
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
                                    <p>{{$item->tanggal_dikirim}}</p>
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
                                        @if ($item->order != null)

                                        @foreach ($item->order->orderitem as $barang)
                                        <tr>
                                            <td class="text-center">{{$barang->nama_produk}}</td>
                                            <td class="text-center">{{$barang->qty}}</td>
                                            <td class="text-center">{{$barang->berat_item}}</td>
                                        </tr>
                                        @endforeach
                                        @endif
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
