@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="">Daftar Pengiriman</h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills mb-3 gap-1" id="myTab3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab"
                                aria-controls="home" aria-selected="true">Semua</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="verif-tab3" data-toggle="tab" href="#verif3" role="tab"
                                aria-controls="verif" aria-selected="false">Penjemputan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="ref-tab3" data-toggle="tab" href="#ref3" role="tab"
                                aria-controls="ref" aria-selected="false">Pengantaran</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="ref-tab3" data-toggle="tab" href="#ref3" role="tab"
                                aria-controls="ref" aria-selected="false">Pengembalian</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="ref-tab3" data-toggle="tab" href="#ref3" role="tab"
                                aria-controls="ref" aria-selected="false">Ditahan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="ref-tab3" data-toggle="tab" href="#ref3" role="tab"
                                aria-controls="ref" aria-selected="false">Selesai</a>
                        </li>
                    </ul>
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
                             a = '<span>'+data+'</span>';
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
                             a = '<span>'+data+'</span>';
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
                             a = '<span>'+data+'</span>';
                        }
                        return a;
                    }
                },
                {
                    data: 'order',
                    render: function (data, row, type, meta) {
                        if (data == null) {
                            a = '<span>-</span>'
                        }else{

                            a = '<span>'+data.orderitem.length+'</span>';
                        }
                        return a;
                    }
                },
                {
                    data: "price",
                    render: function(data, type, row, meta) {
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
                             a = '<span>'+data+'</span>';
                        }
                        return a;
                    }
                }
               
            ],
        });
    });


</script>
    
@endsection
