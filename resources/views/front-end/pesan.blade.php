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
                <div class="heads">
                    <a href="{{route('front-end-notif')}}">Profile</a>
                    <a href="{{route('front-end-npl')}}">NPL</a>
                    <a href="{{route('front-end-pelunasan')}}">Pelunasan Barang Lelang</a>
                    <a href="{{route('front-end-pesan')}}">Notifikasi</a>
                </div>
                <div class="scroll">
                    <table class="table table-bordered">
                        <thead style="position: sticky; top: 0; background-color: rgb(224, 13, 13);">
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Pesan</th>
                                <th>Aksi</th>

                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $id = 1;
                            @endphp
                            @foreach ($data as $item)
                            <tr>
                                <td>{{$id++}}</td>
                                <td>{{$item->judul}}</td>
                                <td>{{$item->pesan}}
                                    @if ($item->refund)
                                    <img style="width:200px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin:5px; padding:0.25rem; border:1px solid #dee2e6; "
                                        src="{{ asset('storage/image/'.$item->refund->bukti) }}" alt="">
                                    @else

                                    @endif
                                </td>
                                <td>
                                    @if ($item->type == 'Pelunasan Barang')
                                    <td>
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal{{$item->id}}">
                                            Beri Ulasan
                                        </button>
                                    </td>
                                    @else

                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @foreach ($data as $item)
    <!-- Modal -->
    <div class="modal fade" id="exampleModal{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Form Beri Ulasan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    
                    <form action="{{route('beri-ulasan', $item->id)}}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label>Bintang <span style="color: red">*</span></label>
                            <select class="form-control" name="bintang">
                                <option value="1">&#9733;</option>
                                <option value="2">&#9733;&#9733;</option>
                                <option value="3">&#9733;&#9733;&#9733;</option>
                                <option value="4">&#9733;&#9733;&#9733;&#9733;</option>
                                <option value="5">&#9733;&#9733;&#9733;&#9733;&#9733;</option>
                            </select>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label>Ulasan Anda <span style="color: red">*</span></label>
                            <textarea name="ulasan" id="" cols="10" rows="5" class="form-control"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    @endforeach
</section>
@endsection
