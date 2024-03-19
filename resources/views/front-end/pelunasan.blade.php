@extends('front-end.layout')
@section('content')
<style>
    .con-kontak {
        background-image: url('asset-lelang/kontak1.png');
        height: 35vh;
        background-position: center;
        background-size: cover;
        width: 100%;
        padding: 150px 50px 50px 50px;
        color: white;
        text-align: center;
    }

    .parent {
        padding: 50px;
        color: white;
        text-align: center;
        display: flex;
        justify-content: center;
    }

    .con-kontak2 {
        background-image: url('asset-lelang/lelang2.jpg');
        height: auto;
        background-position: center;
        background-size: cover;
        width: 100%;
        display: flex;
        justify-content: center;
        padding-top: 100px;
        padding-bottom: 100px;
    }

    .card-kon,
    .card-kon2,
    .card-kon3 {
        background-color: #31869b;
        padding: 40px 40px 10px 40px;
    }

    .card-kon2,
    .card-kon3 {
        margin-left: 40px;
    }

    .a {
        width: 500px;
    }

    .lokasi {
        display: flex;
        justify-content: center;
    }

    .lokasi iframe {
        width: 100%;
        padding: 0px 100px 100px 100px;
    }

    .scroll {
        height: 500px;
        overflow: scroll;
    }

    .heads {
        display: flex;
        justify-content: space-between;
        padding: 0px 10px 10px 10px;
    }

    .button {
        width: 300px;
    }

    .heads a {
        text-decoration: none;
        color: black;
    }

    .scroll {
        height: 500px;
        overflow: scroll;
    }

    @media (max-width: 600px) {
        .parent {
            padding: 20px;
            color: white;
            text-align: center;
            display: block;
        }

        .a {
            width: 200px;
        }

        .card-kon2,
        .card-kon3 {
            margin-top: 10px;
            margin-left: 0px;
        }

        .lokasi iframe {
            width: 100%;
            padding: 20px;
        }
    }

</style>
<section id="kontak">
    <div class="con-kontak">
        <img src="{{ asset('asset-lelang/profile_picture.png') }}" width="150" alt="">
    </div>
    <div class="con-kontak2">
        <div class="card" style="width: 80%;">
            <div class="card-body">
              
                <nav class="navbar navbar-expand-lg bg-primary">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a href="{{route('front-end-notif')}}" class="nav-link">Profile</a>
                                </li>
                                <li class="nav-item">
                                        <a href="{{route('front-end-npl')}}" class="nav-link">NPL</a>

                                </li>
                                <li class="nav-item">
                                    <a class="{{ request()->routeIs('front-end-pelunasan') ? 'text-white active' : '' }} nav-link" href="{{route('front-end-pelunasan')}}">Pelunasan Barang Lelang</a>

                                </li>
                                <li class="nav-item">
                                    <a href="{{route('front-end-pesan')}}" class="nav-link">Pesan</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <div class="scroll mt-2">
                    <table class="table table-bordered">
                        <thead style="position: sticky; top: 0; background-color: rgb(224, 13, 13);">
                            <tr>
                                <th>No</th>
                                <th>Nama Barang Lelang</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $id = 1;
                            @endphp
                            @foreach ($data as $item)
                            <tr>
                                @if (!empty($item->pemenang->bidding))
                                <td>{{$id++}}</td>
                                <td>{{$item->pemenang->bidding->lot_item->barang_lelang->barang}}</td>
                                <td>Rp {{number_format($item->pemenang->nominal - $item->pemenang->npl->harga_item,0,'.','.')}}</td>
                                <td>
                                    @if ($item->pemenang->status_verif == 'Terverifikasi')
                                        
                                    <span class="badge bg-dark">{{$item->pemenang->status_verif}}</span>
                                    @else
                                        
                                    <span class="badge bg-secondary">{{$item->pemenang->status_verif}}</span>
                                    @endif
                                </td>
                                    @if ($item->pemenang->status_verif == '-')
                                    <td>
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal{{$item->id}}">
                                            Bayar Pelunasan
                                        </button>
                                    </td>
                                    @endif
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@foreach ($data as $item)
    @if (!empty($item->pemenang->bidding))
    <!-- Modal -->
    <div class="modal fade" id="exampleModal{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Form Pelunasan Barang</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('pelunasan-barang-lelang', $item->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                        <div class="form-group mb-3">
                            <label>Nama Barang <span style="color: red">*</span></label>
                            <input type="text" class="form-control"  value="{{$item->pemenang->bidding->lot_item->barang_lelang->barang}}"
                                 readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label>Total Transfer<span style="color: red">*</span></label>
                            <input type="text" class="form-control" value="{{number_format($item->pemenang->nominal - $item->pemenang->npl->harga_item,0,'.','.')}}"
                                readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label>Bukti Transfer <span style="color: red">*</span></label>
                            <input type="file" class="form-control" name="bukti" required
                                id="gambar">
                            <div id="preview" class="mt-3"></div>
                        </div>
                        <input type="hidden" name="tipe_pelunasan" value="transfer">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    @endif
    

@endforeach
</section>
@endsection
