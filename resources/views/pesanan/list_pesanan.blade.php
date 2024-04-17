@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="">Daftar Pesanan</h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills mb-3 " id="myTab3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab"
                                aria-controls="home" aria-selected="true">Semua</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="success-tab3" data-toggle="tab" href="#success3" role="tab"
                                aria-controls="success" aria-selected="false">Success</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pending-tab3" data-toggle="tab" href="#pending3" role="tab"
                                aria-controls="pending" aria-selected="false">Pending</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="expire-tab3" data-toggle="tab" href="#expire3" role="tab"
                                aria-controls="expire" aria-selected="false">Expire</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent2">
                        <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="home-tab3">
                            <table class="table table-striped w-100" id="tablepesanan">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Invoice</th>
                                        <th>Nama Pemesan</th>
                                        <th>Status</th>
                                        <th>Total Harga</th>
                                        <th>Total Item</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                        </div>
                        <div class="tab-pane fade" id="success3" role="tabpanel" aria-labelledby="success-tab3">
                            <table class="table table-striped w-100" id="success">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Invoice</th>
                                        <th>Nama Pemesan</th>
                                        <th>Status</th>
                                        <th>Total Harga</th>
                                        <th>Total Item</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                        </div>
                        <div class="tab-pane fade" id="pending3" role="tabpanel" aria-labelledby="pending-tab3">
                            <table class="table table-striped w-100" id="pending">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Invoice</th>
                                        <th>Nama Pemesan</th>
                                        <th>Status</th>
                                        <th>Total Harga</th>
                                        <th>Total Item</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                        </div>
                        <div class="tab-pane fade" id="expire3" role="tabpanel" aria-labelledby="expire-tab3">
                            <table class="table table-striped w-100" id="expire">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Invoice</th>
                                        <th>Nama Pemesan</th>
                                        <th>Status</th>
                                        <th>Total Harga</th>
                                        <th>Total Item</th>
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
                    data: "no_invoice",
                    render: function (data) {
                        if (data == null) {
                            a = `<span>-</span>`
                        } else {
                            a = `<span>` + data + `</span>`
                        }
                        return a;
                    }
                },

                {
                    data: "nama_user",
                },
                {
                    data: "status",
                    render: function (data, type, row, meta) {
                        if (data == "PENDING") {
                            badge = `<span class="badge badge-warning">PENDING</span>`
                        } else if (data == "PAID") {
                            badge = `<span class="badge badge-success">SUCCESS</span>`
                        } else if (data == "EXPIRED") {
                            badge = `<span class="badge badge-dark">EXPIRED</span>`
                        } else if (data == "FAILED") {
                            badge = `<span class="badge badge-danger">FAILED</span>`
                        } else {
                            badge = `<span class="badge badge-danger">ERROR</span>`
                        }
                        return badge;
                    }
                },
                {
                    data: "total_harga_all_item",
                    render: function (data, type, row, meta) {
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
                },
                {
                    data: null,
                    render: function (data, row, type, meta) {
                        a = '<span>' + data.orderitem.length + '</span>'
                        return a;
                    }
                },
                {
                    data: null,
                    render: function (data) {
                        var detailUrl = '/detail-pesanan/' + data.id;
                        var editUrl = '/edit-pesanan/' + data.id;
                        return `
                        <span><a class="btn btn-dark" href="${detailUrl}"><i class="fas fa-search"></i> Detail</a></span>
                    `;
                    },
                },
            ],
        });

        $('#success').DataTable({
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
                        } else {
                            a = `<span>` + data + `</span>`
                        }
                        return a;
                    }
                },

                {
                    data: "nama_user",
                },
                {
                    data: "status",
                    render: function (data, type, row, meta) {
                        if (data == "PENDING") {
                            badge = `<span class="badge badge-warning">PENDING</span>`
                        } else if (data == "PAID") {
                            badge = `<span class="badge badge-success">SUCCESS</span>`
                        } else if (data == "EXPIRED") {
                            badge = `<span class="badge badge-dark">EXPIRED</span>`
                        } else if (data == "FAILED") {
                            badge = `<span class="badge badge-danger">FAILED</span>`
                        } else {
                            badge = `<span class="badge badge-danger">ERROR</span>`
                        }
                        return badge;
                    }
                },
                {
                    data: "total_harga_all_item",
                    render: function (data, type, row, meta) {
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
                },
                {
                    data: null,
                    render: function (data, row, type, meta) {
                        a = '<span>' + data.orderitem.length + '</span>'
                        return a;
                    }
                },
                {
                    data: null,
                    render: function (data) {
                        var detailUrl = '/detail-pesanan/' + data.id;
                        var editUrl = '/edit-pesanan/' + data.id;
                        return `
                        <span><a class="btn btn-dark" href="${detailUrl}"><i class="fas fa-search"></i> Detail</a></span>
                    `;
                    },
                },
            ],
        });

        $('#pending').DataTable({
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
                        } else {
                            a = `<span>` + data + `</span>`
                        }
                        return a;
                    }
                },

                {
                    data: "nama_user",
                },
                {
                    data: "status",
                    render: function (data, type, row, meta) {
                        if (data == "PENDING") {
                            badge = `<span class="badge badge-warning">PENDING</span>`
                        } else if (data == "PAID") {
                            badge = `<span class="badge badge-success">SUCCESS</span>`
                        } else if (data == "EXPIRED") {
                            badge = `<span class="badge badge-dark">EXPIRED</span>`
                        } else if (data == "FAILED") {
                            badge = `<span class="badge badge-danger">FAILED</span>`
                        } else {
                            badge = `<span class="badge badge-danger">ERROR</span>`
                        }
                        return badge;
                    }
                },
                {
                    data: "total_harga_all_item",
                    render: function (data, type, row, meta) {
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
                },
                {
                    data: null,
                    render: function (data, row, type, meta) {
                        a = '<span>' + data.orderitem.length + '</span>'
                        return a;
                    }
                },
                {
                    data: null,
                    render: function (data) {
                        var detailUrl = '/detail-pesanan/' + data.id;
                        var editUrl = '/edit-pesanan/' + data.id;
                        return `
                        <span><a class="btn btn-dark" href="${detailUrl}"><i class="fas fa-search"></i> Detail</a></span>
                    `;
                    },
                },
            ],
        });

        $('#expire').DataTable({
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
                        } else {
                            a = `<span>` + data + `</span>`
                        }
                        return a;
                    }
                },

                {
                    data: "nama_user",
                },
                {
                    data: "status",
                    render: function (data, type, row, meta) {
                        if (data == "PENDING") {
                            badge = `<span class="badge badge-warning">PENDING</span>`
                        } else if (data == "PAID") {
                            badge = `<span class="badge badge-success">SUCCESS</span>`
                        } else if (data == "EXPIRED") {
                            badge = `<span class="badge badge-dark">EXPIRED</span>`
                        } else if (data == "FAILED") {
                            badge = `<span class="badge badge-danger">FAILED</span>`
                        } else {
                            badge = `<span class="badge badge-danger">ERROR</span>`
                        }
                        return badge;
                    }
                },
                {
                    data: "total_harga_all_item",
                    render: function (data, type, row, meta) {
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
                },
                {
                    data: null,
                    render: function (data, row, type, meta) {
                        a = '<span>' + data.orderitem.length + '</span>'
                        return a;
                    }
                },
                {
                    data: null,
                    render: function (data) {
                        var detailUrl = '/detail-pesanan/' + data.id;
                        var editUrl = '/edit-pesanan/' + data.id;
                        return `
                        <span><a class="btn btn-dark" href="${detailUrl}"><i class="fas fa-search"></i> Detail</a></span>
                    `;
                    },
                },
            ],
        });
    });

</script>

@endsection
