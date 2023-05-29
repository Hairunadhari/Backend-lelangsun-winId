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
            <h4>Daftar Promo Produk</h4>
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
            <a href="{{route('form-input-promosi')}}" class="btn btn-success mb-3">+ Tambah</a>
            <div class="table-responsive">
                <table class="table table-bordered">
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
                        @php
                        $no = 1;
                        @endphp
                        @forelse ($data as $index => $d)
                        <tr>
                            <td>{{$index + $data->firstItem()}}</td>
                            <td>{{$d->promosi}}</td>
                            <td>
                                <img src="{{ asset('/storage/image/'.$d->gambar) }}" class="rounded m-2" style="width: 100px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; ">
                            </td>
                            <td>{{$d->diskon}}%</td>
                            @if ($d->status == 'akan datang')
                                <td><span class="badge badge-primary">Coming Soon</span></td>
                            @elseif($d->status == 'sedang berlangsung')
                                <td><span class="badge badge-success">Sedang Berlangsung</span></td>
                            @else
                                <td><span class="badge badge-light">Selesai</span></td>
                            @endif
                            <td>
                                <div class="dropdown d-inline">
                                    <i class="fas fa-ellipsis-v cursor-pointer" style="cursor:pointer"
                                        id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false"></i>
                                    <form action="{{route('deletepromosi', $d->id)}}" method="POST"
                                        onsubmit="return confirm('Apakah anda yakin akan menghapus data ini ?');">
                                        <div class="dropdown-menu" x-placement="bottom-start"
                                            style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                                            <a class="dropdown-item has-icon" href="{{route('detailpromosi',$d->id)}}"><i
                                                    class="fas fa-info-circle"></i>Detail</a>
                                            <a class="dropdown-item has-icon" href="{{ route('editpromosi', $d->id) }}"><i
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
</div>
@endsection
