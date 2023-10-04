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
    .scroll{
        height:500px;
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
                                <th>Nama Barang Lelang</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody >
                            @php
                                $id = 1;
                            @endphp
                            @foreach ($data as $item)
                            <tr>
                                @if (!empty($item->pemenang->bidding))
                                <td>{{$id++}}</td>
                                <td>{{$item->pemenang->bidding->lot_item->barang_lelang->barang}}</td>
                                <td>Rp {{number_format($item->pemenang->nominal)}}</td>
                                <td><span class="badge bg-success">Bayar</span></td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


</section>
@endsection
