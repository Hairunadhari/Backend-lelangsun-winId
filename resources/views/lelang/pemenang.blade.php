@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="w-100">Daftar Pemenang</h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills" id="myTab3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab"
                                aria-controls="home" aria-selected="true">Pemenang Aktif</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab"
                                aria-controls="profile" aria-selected="false">Pemenang Tidak Aktif</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent2">
                        <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="home-tab3">
                            <table class="table table-striped w-100" id="active">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>No Rekening</th>
                                        <th>Nominal</th>
                                        <th>Status Pembayaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">
                            <table class="table table-striped w-100" id="not-active">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>No Rekening</th>
                                        <th>Nominal</th>
                                        <th>Status Pembayaran</th>
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
<script type="text/javascript">
    $(document).ready(function () {
        $('#active').DataTable({
            processing: true,
            ordering: false,
            searching: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: '{{ url()->current() }}',
                data: function (data) {
                    data.status = 'active';
                }
            },
            columns: [{
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "nama_pemilik",
                    render: function (data, type, row, meta) {
                        if (data == null) {
                            return `<span>Bidder Offline</span>`
                        } else {
                            return `<span>`+data+`</span>`
                        }
                    },
                },
                {
                    data: "no_rek",
                    render: function (data, type, row, meta) {
                        if (data == null) {
                            return `<span>-</span>`
                        } else {
                            return `<span>`+data+`</span>`
                        }
                    },
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        if (row.npl == null) {
                            var total = data.nominal;
                        } else {
                            var total = data.nominal - row.npl.harga_item;
                        }
                        
                        if (type === 'display') {
                            return parseInt(total).toLocaleString('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                            });
                        }
                        return total;
                    }
                },
                {
                    data: "status_pembayaran",
                    render: function (data, type, row, meta) {
                        if (data == 'Belum Bayar') {
                            return `<span class="badge bg-primary text-white">Belum Bayar</span>`
                        } else {
                            return `<span class="badge bg-success text-white">Lunas</span>`
                        }
                    },
                },
                {
                    data: null,
                    render: function (data) {
                        var activeUrl = '/superadmin/form-verify-pemenang/' + data.id;
                        return `
                    <a class="btn btn-success" href="${activeUrl}"><i class="fas fa-check"></i></a>
                    `;
                    },
                },
            ],
        });

        $('#not-active').DataTable({
            processing: true,
            ordering: false,
            searching: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: '{{ url()->current() }}',
                data: function (data) {
                    data.status = 'not-active';
                }
            },
            columns: [{
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "nama_pemilik",
                    render: function (data, type, row, meta) {
                        if (data == null) {
                            return `<span>Bidder Offline</span>`
                        } else {
                            return `<span>`+data+`</span>`
                        }
                    },
                },
                {
                    data: "no_rek",
                    render: function (data, type, row, meta) {
                        if (data == null) {
                            return `<span>-</span>`
                        } else {
                            return `<span>`+data+`</span>`
                        }
                    },
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        var total = data.nominal - row.npl.harga_item
                        if (type === 'display') {
                            return parseInt(total).toLocaleString('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                            });
                        }
                        return total;
                    }
                },
                {
                    data: "status_pembayaran",
                    render: function (data, type, row, meta) {
                        if (data == 'Belum Bayar') {
                            return `<span class="badge bg-primary text-white">Belum Bayar</span>`
                        } else {
                            return `<span class="badge bg-success text-white">Lunas</span>`
                        }
                    },
                },
            ],
        });

    });

</script>
@endsection

