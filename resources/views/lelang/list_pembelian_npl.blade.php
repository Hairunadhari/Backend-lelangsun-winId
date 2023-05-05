@extends('app.layouts')
@section('content')
<style>
    nav {
        margin-left: auto;
    }

</style>
<div class="section-header">
    <h1>Data Lelang</h1>
</div>
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Daftar Pembelian Npl</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session('success') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#exampleModal">
                <span class="text">+ Tambah</span>
            </button>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Toko</th>
                            <th scope="col">Logo</th>
                            <th scope="col">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                      
                        @forelse ($data as $d)
                        <tr>
                            <td>{{$no++}}</td>
                            <td>{{$d->toko}}</td>
                            <td>
                                <img src="{{ asset('/storage/image/'.$d->logo) }}" class="rounded" style="width: 100px">
                            </td>
                            <td>
                                <div class="dropdown d-inline">
                                    <i class="fas fa-ellipsis-v cursor-pointer" style="cursor:pointer"
                                        id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false"></i>
                                    <form action="{{route('deletetoko', $d->id)}}" method="POST"
                                        onsubmit="return confirm('Apakah anda yakin akan menghapus data ini ?');">
                                        <div class="dropdown-menu" x-placement="bottom-start"
                                            style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                                            <a class="dropdown-item has-icon" href="{{route('detailtoko',$d->id)}}"><i
                                                    class="fas fa-info-circle"></i>Detail</a>
                                            <a class="dropdown-item has-icon" href="{{ route('edittoko', $d->id) }}"><i
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
            <div class="row">{{ $data->links() }} </div>
        </div>
    </div>
</div>
@endsection
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Input Toko</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('addtoko')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Toko</label>
                        <input type="text" class="form-control" name="toko" required id="exampleInputEmail1">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Logo</label>
                        <input type="file" class="form-control" name="logo" required id="exampleInputEmail1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
