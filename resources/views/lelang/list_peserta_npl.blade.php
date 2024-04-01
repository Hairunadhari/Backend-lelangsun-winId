@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="w-100">Daftar Peserta</h4>
                    <a href="/superadmin/form-tambah-peserta" class="btn btn-success">
                        <span class="text">+ Tambah Peserta</span>
                    </a>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills" id="myTab3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab"
                                aria-controls="home" aria-selected="true">Peserta Aktif</a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab"
                                aria-controls="profile" aria-selected="false">Peserta Tidak Aktif</a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link" id="verif-tab3" data-toggle="tab" href="#verif3" role="tab"
                                aria-controls="verif" aria-selected="false">Verifikasi NPL</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="ref-tab3" data-toggle="tab" href="#ref3" role="tab"
                                aria-controls="ref" aria-selected="false">Refund NPL</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent2">
                        <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="home-tab3">
                            <table class="table table-striped w-100" id="active">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Telpon</th>
                                        <th>Alamat</th>
                                        <th>NPL</th>
                                        <th>Email Verified</th>
                                        <th>Opsi</th>
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
                                        <th>Email</th>
                                        <th>Telpon</th>
                                        <th>Alamat</th>
                                        <th>NPL</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="verif3" role="tabpanel" aria-labelledby="verif-tab3">
                            <table class="table table-striped w-100" id="verify">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Nominal</th>
                                        <th>Bukti Transfer</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="ref3" role="tabpanel" aria-labelledby="ref-tab3">
                            <table class="table table-striped w-100" id="ref">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Kode NPL</th>
                                        <th>Nominal</th>
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
<script type="text/javascript">
    $(document).ready(function () {
        $('#active').DataTable({
            processing: true,
            ordering: false,
            searching: true,
            serverSide: true,
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
                    data: "name",
                },
                {
                    data: "email",
                },
                {
                    data: "no_telp",
                    render: function (data) {
                        if (data != null) {
                            return `<span>`+data+`</span>`
                        } else {
                            return `<span>-</span>`
                            
                        }
                    }
                },
                {
                    data: "alamat",
                    render: function (data) {
                        if (data != null) {
                            return `<span>`+data+`</span>`
                        } else {
                            return `<span>-</span>`
                            
                        }
                    }
                    
                },
                {
                    data: null,
                    render: function (data, row, type, meta) {
                        var npl = 'npl/' + data.id;
                        var total =  data.npl.length;
                        return `<a class="btn btn-warning fs-5" href="${npl}">${total}</a>`;
                    }
                },
                {
                    data: 'email_verified_at',
                    render: function (data) {
                        if (data == null) {
                            status = `<span class="badge badge-dark">Belum Aktif</span>`;
                        } else {
                            status = `<span class="badge badge-success">Aktif</span>`;
                            
                        }
                        return status;
                    }
                },
                {
                    data: null,
                    render: function (data) {
                        var deleteUrl = '/superadmin/delete-peserta-npl/' + data.id;
                        var editUrl = '/superadmin/edit-peserta-npl/' + data.id;
                        if (data.email_verified_at == null) {
                            action = `
                                    <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan menghapus data ini ?');">
                                    <span><a class="btn btn-sm btn-primary" href="${editUrl}"><i class="fas fa-edit"></i></a></span>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="PUT">
                                    <a class="btn btn-sm btn-success" onclick="return confirm('Apakah anda ingin mengaktifkan verifikasi email data ini?');" href="/superadmin/aktifkan-email-peserta/${data.id}"><i class="fas fa-user-check"></i></a>
                                    </form>
                                `;
                        } else {
                            action = `
                                    <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan menghapus data ini ?');">
                                    <span><a class="btn btn-sm btn-primary" href="${editUrl}"><i class="fas fa-edit"></i></a></span>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="PUT">
                                    </form>
                                `;
                        }
                        return action;
                    },
                },
            ],
        });

        $('#not-active').DataTable({
            processing: true,
            ordering: false,
            searching: true,
            serverSide: true,
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
                    data: "name",
                },
                {
                    data: "email",
                },
                {
                    data: "no_telp",
                    render: function (data) {
                        if (data != null) {
                            return `<span>`+data+`</span>`
                        } else {
                            return `<span>-</span>`
                            
                        }
                    }
                },
                {
                    data: "alamat",
                    render: function (data) {
                        if (data != null) {
                            return `<span>`+data+`</span>`
                        } else {
                            return `<span>-</span>`
                            
                        }
                    }
                },
                {
                    data: null,
                    render: function (data) {
                        var npl = '/npl/' + data.id;
                        return `<a class="btn btn-success fs-5" href="${npl}">0</a>`;
                    }
                },
                {
                    data: null,
                    render: function (data) {
                        var activeUrl = '/superadmin/active-peserta-npl/' + data.id;
                        return `
                    <form action="${activeUrl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan mengaktifkan data ini ?');">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">
                        <button class="btn btn-success" type="submit">Aktifkan</button>
                    </form>
                    `;
                    },
                },
            ],
        });

        $('#verify').DataTable({
            processing: true,
            ordering: false,
            searching: true,
            serverSide: true,
            ajax: {
                url: '{{ url()->current() }}',
                data: function (data) {
                    data.status = 'verifikasi';
                }
            },
            columns: [{
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "user.name",
                },
                {
                    data: "user.email",
                },
                {
                    data: "nominal",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            // Mengubah data menjadi format mata uang dengan simbol IDR
                            return "Rp " + data.toLocaleString('id-ID', {
                                minimumFractionDigits: 0
                            });
                        }
                        return data;
                    }
                },
                {
                    data: "bukti",
                    render: function (data) {
                        return '<a href="/storage/image/' + data + '" target="_blank"><img src="/storage/image/' + data + '" style="width: 100px; height: 100px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin: 5px; padding: 0.25rem; border: 1px solid #dee2e6;"></a>';
                        },
                },
                {
                    data: null,
                    render: function (data) {
                        var activeUrl = '/superadmin/verify-npl/' + data.id;
                        return `
                    <form action="${activeUrl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan memverifikasi data ini ?');">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">
                        <button class="btn btn-success" type="submit"><i class="fas fa-check"></i></button>
                    </form>
                    `;
                    },
                },
            ],
        });
        $('#ref').DataTable({
            processing: true,
            ordering: false,
            searching: true,
            serverSide: true,
            ajax: {
                url: '{{ url()->current() }}',
                data: function (data) {
                    data.status = 'Pengajuan';
                }
            },
            columns: [{
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "npl.user.name",
                    render: function (data, type, row, meta) {
                        if (data == null) {
                            return `-`;
                        } else {
                            return data;
                        }
                        return data;
                    },
                },
                {
                    data: "npl.user.email",
                },
                {
                    data: "npl.kode_npl",
                },
                {
                    data: "npl.harga_item",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            // Mengubah data menjadi format mata uang dengan simbol IDR
                            return "Rp " + data.toLocaleString('id-ID', {
                                minimumFractionDigits: 0
                            });
                        }
                        return data;
                    }
                },
                {
                    data: null,
                    render: function (data) {
                        var refund = '/superadmin/form-refund/' + data.id;
                        return `
                        <span><a class="btn btn-success" href="${refund}"><i class="fas fa-check"></i></a></span>
                    `;
                    },
                },
            ],
        });
    });

</script>
@endsection
