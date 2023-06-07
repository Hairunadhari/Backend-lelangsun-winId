@extends('app.layouts')
@section('content')
<style>
    nav {
        margin-left: auto;
    }

</style>
<div class="section-header">
    <h1>Data E-commerce</h1>
</div>
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Daftar Order</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama Pemesan</th>
                            <th scope="col">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $d)
                        <tr>
                            <td>{{$index + $data->firstItem() }}</td>
                            <td>{{$d->user->name}}</td>
                            <td>
                                <div class="dropdown d-inline">
                                    <i class="fas fa-ellipsis-v cursor-pointer" style="cursor:pointer" id="dropdownMenuButton2"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                    <form action="{{route('delete-kategori-produk', $d->id)}}" method="POST"
                                        onsubmit="return confirm('Apakah anda yakin akan menghapus data ini ?');">
                                        <div class="dropdown-menu" x-placement="bottom-start"
                                            style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                                            <a class="dropdown-item has-icon"
                                                href="{{route('detail-kategori-produk',$d->id)}}"><i
                                                    class="fas fa-info-circle"></i>Detail</a>
                                            <a class="dropdown-item has-icon"
                                                href="{{ route('edit-kategori-produk', $d->id) }}"><i
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
        </div>
    </div>
</div>
@endsection
