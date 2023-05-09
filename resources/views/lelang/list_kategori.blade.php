@extends('app.layouts')
@section('content')
<div class="section-header">
    <h1>Data Lelang</h1>
</div>
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Daftar Kategori Barang Lelang</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible text-center fade show" role="alert">
                <strong>{{ session('success') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @elseif ($errors->has('kategori'))
            <div class="alert alert-danger alert-dismissible text-center fade show" role="alert">
                <strong>Kategori sudah Terdaftar!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#exampleModal2">
                <span class="text">+ Tambah</span>
            </button>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $index => $d)
                    <tr>
                        <td>{{$index + $data->firstItem() }}</td>
                        <td>{{ucfirst($d->kategori)}}</td>
                        <td>
                            <div class="dropdown d-inline">
                                <i class="fas fa-ellipsis-v cursor-pointer" style="cursor:pointer" id="dropdownMenuButton2"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                <form action="{{route('delete-kategori-lelang', $d->id)}}" method="POST"
                                    onsubmit="return confirm('Apakah anda yakin akan menghapus data ini ?');">
                                    <div class="dropdown-menu" x-placement="bottom-start"
                                        style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                                        <a class="dropdown-item has-icon"
                                            href="{{route('detail-kategori-lelang',$d->id)}}"><i
                                                class="fas fa-info-circle"></i>Detail</a>
                                        <a class="dropdown-item has-icon"
                                            href="{{ route('edit-kategori-lelang', $d->id) }}"><i
                                                class="far fa-edit"></i>Edit</a>
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger " style="margin-left: 20px;" type="submit"><i
                                                class="far fa-trash-alt"></i> Hapus</button>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-left:20px">
            {{ $data->links() }}
        </div>
    </div>
</div>
@endsection
<!-- Modal -->
<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Input Kategori Lelang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('add-kategori-lelang')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kategori</label>
                        <input type="text" class="form-control" name="kategori" required>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-warning" id="resetButton">Reset</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
