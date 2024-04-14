@extends('app.layouts')
@section('content')
<style>
     #pending.active {
        background-color: #FFC107;
    }
     #profile-tab3.active {
        background-color: #63ED7A;
    }

    /* Mengatur warna hitam untuk tautan aktif dengan ID "contact-tab3" */
    #contact-tab3.active {
        background-color: #CDD3D8;
    }
    .nav-pills{
        gap: 1rem;
    }
</style>
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="">Daftar Pesanan</h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills mb-3" id="myTab3" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" id="pending" data-toggle="tab" href="#home3" role="tab" aria-controls="home" aria-selected="true">Pending</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab" aria-controls="profile" aria-selected="false">Success</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="contact-tab3" data-toggle="tab" href="#contact3" role="tab" aria-controls="contact" aria-selected="false">Expired</a>
                        </li>
                      </ul>

                      <div class="tab-content" id="myTabContent2">
                        <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="pending">
                            <table class="table table-striped w-100" id="payment-pending">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Invoice</th>
                                        <th>No Resi</th>
                                        <th>Nama</th>
                                        <th>Status Pembayaran</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">
                            <table class="table table-striped w-100" id="payment-success">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Invoice</th>
                                        <th>No Resi</th>
                                        <th>Nama</th>
                                        <th>Status Pembayaran</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="contact3" role="tabpanel" aria-labelledby="contact-tab3">
                            <table class="table table-striped w-100" id="payment-expired">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Invoice</th>
                                        <th>No Resi</th>
                                        <th>Nama </th>
                                        <th>Status Pembayaran</th>
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
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#payment-pending').DataTable({
            processing: true,
            ordering: false,
            serverSide: true,
            ajax: {
                url: '{{ url()->current() }}',
                data: function (data) {
                    data.status = 'PENDING';
                }
            },
            columns: [{
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "no_invoice",
                    render: function (data) {
                        if (data == null) {
                            a = `<span>-</span>`
                        }else{
                            a= `<span>`+data+`</span>`
                        }
                        return a;
                    }
                },
                {
                    data: "no_resi",
                    render: function (data) {
                        if (data == null) {
                            a = `<span>-</span>`
                        }else{
                            a= `<span>`+data+`</span>`
                        }
                        return a;
                    }
                },
                {
                    data: "nama_user",
                },
                {
                    data: "status",
                    render: function(data, type, row, meta) {
                        badge = `<span class="badge badge-warning"><i class="fas fa-stopwatch"></i> Pending</span>`
                        return badge;
                    }
                },
                {
                data: null,
                render: function (data) {
                    var detailUrl = '/detail-pesanan/' + data.id;
                    var editUrl = '/edit-pesanan/' + data.id;
                    return `
                        <span><a class="btn btn-secondary" href="${detailUrl}"><i class="fas fa-search"></i> Detail</a></span>
                    `;
                },
            },
            ],
        });

        $('#payment-success').DataTable({
            processing: true,
            ordering: false,
            serverSide: true,
            ajax: {
                url: '{{ url()->current() }}',
                data: function (data) {
                    data.status = 'PAID';
                }
            },
            columns: [{
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "no_invoice",
                    render: function (data) {
                        if (data == null) {
                            a = `<span>-</span>`
                        }else{
                            a= `<span>`+data+`</span>`
                        }
                        return a;
                    }
                },
                {
                    data: "no_resi",
                    render: function (data) {
                        if (data == null) {
                            a = `<span>-</span>`
                        }else{
                            a= `<span>`+data+`</span>`
                        }
                        return a;
                    }
                },
                {
                    data: "nama_user",
                },
                {
                    data: "status",
                    render: function(data, type, row, meta) {
                            badge = `<span class="badge badge-success"><i class="fas fa-check"></i> Success</span>`
                        return badge;
                    }
                },
                {
                data: null,
                render: function (data) {
                    var detailUrl = '/detail-pesanan/' + data.id;
                    var editUrl = '/edit-pesanan/' + data.id;
                    return `
                        <span><a class="btn btn-secondary" href="${detailUrl}"><i class="fas fa-search"></i> Detail</a></span>
                    `;
                },
            },
            ],
        });

        $('#payment-expired').DataTable({
            processing: true,
            ordering: false,
            serverSide: true,
            ajax: {
                url: '{{ url()->current() }}',
                data: function (data) {
                    data.status = 'EXPIRED';
                }
            },
            columns: [{
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "no_invoice",
                    render: function (data) {
                        if (data == null) {
                            a = `<span>-</span>`
                        }else{
                            a= `<span>`+data+`</span>`
                        }
                        return a;
                    }
                },
                {
                    data: "no_resi",
                    render: function (data) {
                        if (data == null) {
                            a = `<span>-</span>`
                        }else{
                            a= `<span>`+data+`</span>`
                        }
                        return a;
                    }
                },
                {
                    data: "nama_user",
                },
                {
                    data: "status",
                    render: function(data, type, row, meta) {
                        badge = `<span class="badge badge-dark">EXPIRED</span>`;

                        return badge;
                    }
                },
                {
                data: null,
                render: function (data) {
                    var detailUrl = '/detail-pesanan/' + data.id;
                    var editUrl = '/edit-pesanan/' + data.id;
                    return `
                        <span><a class="btn btn-secondary" href="${detailUrl}"><i class="fas fa-search"></i> Detail</a></span>
                    `;
                },
            },
            ],
        });
    });


</script>
    
@endsection
