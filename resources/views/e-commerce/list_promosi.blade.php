@extends('app.layouts')
@section('content')
<style>
    nav {
        margin-left: auto;
    }

</style>
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="w-100">Daftar Toko</h4>
                    <a href="{{route('form-input-promosi')}}" class="btn btn-success">+ Tambah</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible text-center fade show" role="alert">
                        <strong>{{ session('success') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <table class="table table-striped" id="tablepromo-produk">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama Promosi</th>
                                <th scope="col">Gambar Promo</th>
                                <th scope="col">Diskon</th>
                                <th scope="col">Status Promo</th>
                                <th scope="col">Opsi</th>
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
        $('#tablepromo-produk').DataTable({
            processing: true,
            ordering: false,
            fixedColumns: true,
            // fixedHeader: true,
            ajax: '{{ url()->current() }}',
            columns: [{
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "promosi",
                },
                {
                    data: "gambar",
                    render: function (data) {
                        return '<img src="/storage/image/' + data +
                            '"style="width: 150px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin:5px; ">';
                    },
                },
                {
                    data: "diskon",
                    render: function(data, type, row, meta) {
                        return data + '%';
                    }
                },
                {
                    data: "status",
                    render: function(data, type, row, meta) {
                        if (data == "akan datang") {
                            badge = `<span class="badge badge-primary">Coming Soon</span>`
                        } else if (data == "sedang berlangsung") {
                            badge = `<span class="badge badge-success">Sedang Berlangsung</span>`
                        } else if (data == "selesai") {
                            badge = `<span class="badge badge-light">Selesai</span>`
                        } 
                        return badge;
                    }
                },
                {
                data: null,
                render: function (data) {
                    var deleteUrl = '/deletepromosi/' + data.id;
                    var editUrl = '/editpromosi/' + data.id;
                    var detailUrl = '/detailpromosi/' + data.id;
                    return `
                    <div class="dropdown d-inline">
                        <i class="fas fa-ellipsis-v cursor-pointer" style="cursor:pointer" id="dropdownMenuButton2"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                        <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan menghapus data ini ?');">
                            <div class="dropdown-menu" x-placement="bottom-start"
                                style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item has-icon" href="${detailUrl}"><i class="fas fa-info-circle"></i>Detail</a>
                                <a class="dropdown-item has-icon" href="${editUrl}"><i class="far fa-edit"></i>Edit</a>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button class="btn btn-danger" style="margin-left: 20px;" type="submit"><i class="far fa-trash-alt"></i> Hapus</button>
                            </div>
                        </form>
                    </div>
                    `;
                },
            },
            ],
        });
    });

</script>
@endsection
