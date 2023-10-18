@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="w-100">Daftar Toko</h4>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#tokomodal">
                        <span class="text">+ Tambah Toko</span>
                    </button>
                    <button type="button" class="btn btn-success ml-1" data-toggle="modal" data-target="#tokoakun">
                        <span class="text">+ Tambah Toko & Akun</span>
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
                        var deleteUrl = '/deletetoko/' + data.id;
                        var editUrl = '/edittoko/' + data.id;
                        return `
                    <div class="dropdown d-inline">
                        <i class="fas fa-ellipsis-v cursor-pointer" style="cursor:pointer" id="dropdownMenuButton2"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                        <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan menghapus Toko ini? jika ya maka semua kategori dan produk yg ada di toko ini akan terhapus');">
                            <div class="dropdown-menu" x-placement="bottom-start"
                                style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item has-icon" href="${editUrl}"><i class="far fa-edit"></i>Edit</a>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="PUT">
                                <button class="btn btn-danger" style="margin-left: 20px;" type="submit"><i class="far fa-trash-alt"></i> Hapus</button>
                            </div>
                        </form>
                    </div>
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
                        var activeUrl = '/activetoko/' + data.id;
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

@section('modal')
<!-- Modal -->
<div class="modal fade" id="tokomodal" tabindex="-1" role="dialog" aria-labelledby="tokoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tokoLabel">Form Input Toko</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('addtoko')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Toko <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="toko" required id="exampleInputEmail1">
                    </div>
                    <div class="form-group">
                        <label class="">Logo <span style="color: red">*</span></label>
                        <div class="col-sm-12 col-md-7">
                            <div id="image-preview" class="image-preview">
                                <label for="image-upload" id="image-label">Choose File</label>
                                <input type="file" name="logo" id="image-upload" required>
                            </div>
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

<div class="modal fade" id="tokoakun" tabindex="-1" role="dialog" aria-labelledby="tokoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tokoLabel">Form Input Toko & Akun</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('add-admin')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Toko <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="toko" value="{{ old('toko') }}"
                            autofocus required>
                            @if ($errors->has('toko'))
                        <small class="text-danger">Max 280 karakter!</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="">Logo <span style="color: red">*</span></label>
                        <div class="col-sm-12 col-md-7">
                            <div id="image-preview" class="image-preview">
                                <label for="image-upload" id="image-label">Choose File</label>
                                <input type="file" name="logo" id="image-upload" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name">Nama Pemilik<span style="color: red">*</span></label>
                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" autofocus required>
                        @if ($errors->has('name'))
                        <small class="text-danger">Max 280 karakter!</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Role <span style="color: red">*</span></label>
                        <select class="form-control selectric" name="role_id" required>
                            @foreach ($role as $item)
                            <option value="{{ $item->id }}">{{ $item->role }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span style="color: red">*</span></label>
                        <input id="email" type="email" class="form-control" name="email" required>
                        @if ($errors->has('email'))
                        <small class="text-danger">Email Sudah Terdaftar!</small>
                        @endif
                    </div>
                    <div class=" form-group">
                        <label for="password" class="d-block">Password <span style="color: red">*</span></label>
                        <input id="password" type="password" class="form-control" name="password">
                        @if ($errors->has('password'))
                        <small class="text-danger">Password harus memiliki 10 karakter!</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="d-block">Password Confirmation <span
                                style="color: red">*</span></label>
                        <input id="password_confirmation" type="password" class="form-control"
                            name="password_confirmation" required>
                        @if ($errors->has('password_confirmation'))
                        <small class="text-danger">Konfirmasi Password Tidak Cocok!</small>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
