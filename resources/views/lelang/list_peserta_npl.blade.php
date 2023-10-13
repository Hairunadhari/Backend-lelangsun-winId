@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="w-100">Daftar Peserta</h4>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#pembeliannplmodal">
                        <span class="text">+ Tambah Peserta</span>
                    </button>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills" id="myTab3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab"
                                aria-controls="home" aria-selected="true">Peserta Aktif</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab"
                                aria-controls="profile" aria-selected="false">Peserta Tidak Aktif</a>
                        </li>
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
                                        <th>Tgl Transfer</th>
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
                    data: "nama",
                },
                {
                    data: "email",
                },
                {
                    data: "no_hp",
                },
                {
                    data: "alamat",
                },
                {
                    data: null,
                    render: function (data, row, type, meta) {
                        var npl = '/npl/' + data.id;
                        var total =  data.npl.length;
                        return `<a class="btn btn-warning fs-5" href="${npl}">${total}</a>`;
                    }
                },
                {
                    data: null,
                    render: function (data) {
                        var deleteUrl = '/delete-peserta-npl/' + data.id;
                        var editUrl = '/edit-peserta-npl/' + data.id;
                        return `
                <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan menghapus data ini ?');">
                  <span><a class="btn btn-primary" href="${editUrl}"><i class="fas fa-edit"></i></a></span>
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <input type="hidden" name="_method" value="PUT">
                  <button class="btn btn-danger" type="submit"><i class="far fa-trash-alt"></i></button>
                </form>
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
                    data: "nama",
                },
                {
                    data: "email",
                },
                {
                    data: "no_hp",
                },
                {
                    data: "alamat",
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
                        var activeUrl = '/active-peserta-npl/' + data.id;
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
                    data: "peserta_npl.nama",
                },
                {
                    data: "peserta_npl.email",
                },
                {
                    data: "tgl_transfer",
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
                        var activeUrl = '/verify-npl/' + data.id;
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
                    data: "npl.peserta_npl.nama",
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
                    data: "npl.peserta_npl.email",
                },
                {
                    data: "npl.no_npl",
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
                        var activeUrl = '/verify-npl/' + data.id;
                        var refund = '/form-refund/' + data.id;
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

@section('modal')
<style>
    .form-group img {
        padding: 0rem;
        border: 1px solid #dee2e6;
    }

</style>
<!-- Modal -->
<div class="modal fade" id="pembeliannplmodal" tabindex="-1" role="dialog" aria-labelledby="pembeliannpllabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pembeliannpllabel">Form Input Peserta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('add-peserta-npl')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="nama" required>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label>Email <span style="color: red">*</span></label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="form-group col-6">
                            <label>Telepon <span style="color: red">*</span></label>
                            <input type="text" class="form-control" name="no_hp" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Alamat <span style="color: red">*</span></label>
                        <textarea class="form-control" name="alamat"></textarea>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label>NIK <span style="color: red">*</span></label>
                            <input type="text" class="form-control" name="nik" required>
                        </div>
                        <div class="form-group col-6">
                            <label>NPWP <span style="color: red">*</span></label>
                            <input type="text" class="form-control" name="npwp" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label>Foto KTP <span style="color: red">*</span></label>
                            <input type="file" class="form-control" name="foto_ktp" required id="gambarktp">
                            <div id="previewktp" class="mt-3"></div>
                        </div>
                        <div class="form-group col-6">
                            <label>Foto NPWP <span style="color: red">*</span></label>
                            <input type="file" class="form-control" name="foto_npwp" required id="gambarnpwp">
                            <div id="previewnpwp" class="mt-3"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function previewgambarktp() {
        var preview = document.querySelector('#previewktp');

        // Hapus semua elemen child di dalam elemen #preview
        while (preview.firstChild) {
            preview.removeChild(preview.firstChild);
        }

        if (this.files) {
            [].forEach.call(this.files, readAndPreview);
        }

        function readAndPreview(file) {
            var reader = new FileReader();
            reader.addEventListener("load", function () {
                var image = new Image();
                image.width = 200;
                image.title = file.name;
                image.src = this.result;
                preview.appendChild(image);
            }, false);
            reader.readAsDataURL(file);
        }
    }
    document.querySelector('#gambarktp').addEventListener("change", previewgambarktp);

    function previewgambarnpwp() {
        var preview = document.querySelector('#previewnpwp');

        // Hapus semua elemen child di dalam elemen #preview
        while (preview.firstChild) {
            preview.removeChild(preview.firstChild);
        }

        if (this.files) {
            [].forEach.call(this.files, readAndPreview);
        }

        function readAndPreview(file) {
            var reader = new FileReader();
            reader.addEventListener("load", function () {
                var image = new Image();
                image.width = 200;
                image.title = file.name;
                image.src = this.result;
                preview.appendChild(image);
            }, false);
            reader.readAsDataURL(file);
        }
    }
    document.querySelector('#gambarnpwp').addEventListener("change", previewgambarnpwp);

</script>

<!-- /.container-fluid -->
@endsection
