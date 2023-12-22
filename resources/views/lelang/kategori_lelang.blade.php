@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="w-100">Daftar Kategori</h4>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#kategorilelangmodal">
                        <span class="text">+ Tambah</span>
                    </button>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills" id="myTab3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab"
                                aria-controls="home" aria-selected="true">Aktif</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab"
                                aria-controls="profile" aria-selected="false">Tidak Aktif</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent2">
                        <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="home-tab3">
                            <table class="table table-striped w-100" id="kategori-lelang">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kategori</th>
                                        <th>Kelipatan Bidding</th>
                                        <th>Harga NPL</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">
                            <table class="table table-striped w-100" id="kategorilelang-notactive"
                                style="width:100% !important;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kategori</th>
                                        <th>Kelipatan Bidding</th>
                                        <th>Harga NPL</th>
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
        $('#kategori-lelang').DataTable({
            // responsive: true,
            processing: true,
            ordering: false,
            // fixedHeader: true,
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
                    data: "kategori",
                },
                {
                    data: "kelipatan_bidding",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            // Mengubah data menjadi format mata uang dengan simbol IDR
                            return data.toLocaleString('id-ID', {
                                minimumFractionDigits: 0
                            });
                        }
                        return data;
                    }
                },
                {
                    data: "harga_npl",
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
                    var deleteUrl = '/superadmin/delete-kategori-lelang/' + data.id;
                    var editUrl = '/superadmin/edit-kategori-lelang/' + data.id;
                    return `
                        <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan menghapus data ini ?');">
                        <span><a class="btn btn-primary" href="${editUrl}"><i class="far fa-edit"></i>Edit</a></span>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">
                        <button class="btn btn-danger" type="submit"><i class="far fa-trash-alt"></i> Hapus</button>
                        </form>
                    `;
                    },
                },
            ],
        });
    });

    $(document).ready(function () {
        $('#kategorilelang-notactive').DataTable({
            // responsive: true,
            processing: true,
            ordering: false,
            // fixedHeader: true,
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
                    data: "kategori",
                },
                {
                    data: "kelipatan_bidding",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            // Mengubah data menjadi format mata uang dengan simbol IDR
                            return data.toLocaleString('id-ID', {
                                minimumFractionDigits: 0
                            });
                        }
                        return data;
                    }
                },
                {
                    data: "harga_npl",
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
                    var activeurl = '/superadmin/active-kategori-lelang/' + data.id;
                    return `
                        <form action="${activeurl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan mengaktifkan data ini ?');">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">
                        <button class="btn btn-success" type="submit">Aktifkan</button>
                        </form>
                    `;
                    },
                },
            ],
        });
    });

</script>
@endsection
@section('modal')
<!-- Modal -->
<div class="modal fade" id="kategorilelangmodal" tabindex="-1" role="dialog" aria-labelledby="kategorilelangmodallabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kategorilelangmodallabel">Form Input Kategori Lelang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('superadmin.add-kategori-lelang')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kategori <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="kategori" required>
                    </div>
                    <div class="form-group">
                        <label>Kelipatan Bidding <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="kelipatan_bidding" onkeyup="formatNumber(this)"  required>
                    </div>
                    <div class="form-group">
                        <label>Harga NPL <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="harga_npl" onkeyup="hargaNPL(this)" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function hargaNPL(input) {
        // Menghilangkan karakter selain angka
        var num = input.value.replace(/[^0-9]/g, '');

        // Memformat angka menjadi format ribuan dan desimal
        var formattedNum = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(num);

        // Memasukkan nilai format ke dalam input
        input.value = formattedNum;
    }
    function formatNumber(input) {
        // Menghilangkan karakter selain angka
        var num = input.value.replace(/[^0-9]/g, '');

        // Memformat angka menjadi format ribuan dan desimal
        var formattedNum = new Intl.NumberFormat('id-ID', {
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(num);

        // Memasukkan nilai format ke dalam input
        input.value = formattedNum;
    }
</script>
@endsection

