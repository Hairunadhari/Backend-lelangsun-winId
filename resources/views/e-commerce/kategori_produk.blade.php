@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="w-100">Daftar Kategori Produk</h4>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#kategoriprodukmodal">
                        <span class="text">+ Tambah</span>
                    </button>
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
                            <table class="table table-striped w-100" id="kategori">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kategori</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">
                            <table class="table table-striped w-100" id="kategori-notactive">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kategori</th>
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
        $('#kategori').DataTable({
            // responsive: true,
            processing: true,
            ordering: false,
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
                    data: "kategori",
                },
                {
                data: null,
                render: function (data) {
                    var deleteUrl = '/delete-kategori-produk/' + data.id;
                    var editUrl = '/edit-kategori-produk/' + data.id;
                    return `
                    <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan menghapus kategori ini ? jika ya maka semua produk yang ada di kategori ini akan terhapus');">
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
        $('#kategori-notactive').DataTable({
            // responsive: true,
            processing: true,
            ordering: false,
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
                    data: "kategori",
                },
                {
                data: null,
                render: function (data) {
                    var activeUrl = '/active-kategori-produk/' + data.id;
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
    });

</script>
@endsection
@section('modal')
<!-- Modal -->
<div class="modal fade" id="kategoriprodukmodal" tabindex="-1" role="dialog" aria-labelledby="kategoriProdukLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kategoriProdukLabel">Form Input Kategori Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('add-kategori-produk')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @if (Auth::user()->role->role == 'Super Admin')
                    <div class="form-group">
                        <label>Nama Toko <span style="color: red">*</span></label>
                        <select class="form-control selectric" name="toko_id" required>
                            @foreach ($toko as $item)
                            <option value="{{ $item->id }}">{{ $item->toko }}</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <div class="form-group" hidden>
                        <label>Nama Toko <span style="color: red">*</span></label>
                        <input type="text" value="{{$idToko->id}}" name="toko_id">
                    </div>
                    @endif
                    <div class="form-group">
                        <label>Kategori <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="kategori" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
