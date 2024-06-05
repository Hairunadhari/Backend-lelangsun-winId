@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="w-100">Daftar Toko</h4>
                    {{-- <button type="button" class="btn btn-success" data-toggle="modal" data-target="#tokomodal">
                        <span class="text">+ Tambah Toko</span>
                    </button> --}}
                    <a href="{{route('superadmin.tambah-admin')}}" class="btn btn-success" style="margin-left: 10px">+ Tambah Toko & Admin</a>
                </div>
                <div class="card-body">
                    {{-- <ul class="nav nav-pills" id="myTab3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab"
                                aria-controls="home" aria-selected="true">Aktif</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab"
                                aria-controls="profile" aria-selected="false">Tidak Aktif</a>
                        </li>
                    </ul> --}}
                    <div class="tab-content" id="myTabContent2">
                        <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="home-tab3">
                            <table class="table table-striped w-100" id="toko">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Toko</th>
                                        <th>Logo</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">
                            <table class="table table-striped w-100" id="toko-notactive">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Toko</th>
                                        <th>Logo</th>
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
         setTimeout(() => {
        $('#toko').DataTable({
            processing: true,
            ordering: false,
            serverSide: true,
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
                    data: "toko",
                },
                {
                    data: "logo",
                    render: function (data) {
                        return '<img src="/storage/image/' + data +
                            '"style="width: 150px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin:5px; ">';
                    },
                },
                {
                    data: null,
                    render: function (data) {
                        var deleteUrl = '/superadmin/deletetoko/' + data.id;
                        var editUrl = '/superadmin/edittoko/' + data.id;
                        return `
                        <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan menghapus Toko ini? jika ya maka semua kategori dan produk yg ada di toko ini akan terhapus');">
                                <span><a class="btn btn-primary" href="${editUrl}"><i class="far fa-edit"></i>Edit</a></span>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="PUT">
                                <button class="btn btn-danger"  type="submit"><i class="far fa-trash-alt"></i> Hapus</button>
                        </form>
                    `;
                    },
                },
            ],
        });

        $('#toko-notactive').DataTable({
            processing: true,
            ordering: false,
            serverSide: true,
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
                    data: "toko",
                },
                {
                    data: "logo",
                    render: function (data) {
                        return '<img src="/storage/image/' + data +
                            '"style="width: 150px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin:5px; ">';
                    },
                },
                {
                    data: null,
                    render: function (data) {
                        var activeUrl = '/superadmin/activetoko/' + data.id;
                        return `
                    <form action="${activeUrl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan mengAktifkan Kembali Toko ini ?');">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">
                        <button class="btn btn-success" type="submit">Aktifkan</button>
                        </form>
                    `;
                    },
                },
            ],
        });
         }, 2500);
    });

</script>
@endsection