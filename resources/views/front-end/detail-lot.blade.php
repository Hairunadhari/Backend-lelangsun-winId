@extends('front-end.layout')
@section('content')
<style>
    #satu {
        background-image: url('/asset-lelang/detail_event.jpg');
        height: auto;
        background-position: left;
        background-size: cover;
        background-repeat: no-repeat;
        width: 100%;
        padding: 50px;
        color: white;
    }

    #satu .bungkus-lot {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(18rem, 1fr));
        grid-gap: 20px;
        /* Spasi antara card */
        padding: 0 20px;
        /* Spasi kiri dan kanan dari container */
        justify-content: center;
    }

    #satu .items {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(13rem, 1fr));
        grid-gap: 0px;
        /* Spasi antara card */
        padding: 0px;
        /* Spasi kiri dan kanan dari container */
        justify-content: center;
    }

    #satu .bungkus-lot img {
        height: 200px;
    }

    .item-barang img {
        width: 200px;
        height: 200px;
    }

    .items img {
        box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px;
        margin: 5px;
        padding: 0.25rem;
        border: 1px solid #dee2e6;
    }

    .img-card-p2 {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    h5 {
        box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px;
        margin: 5px;
        padding: 5px;
    }

    .card-text {
        border: 1px solid white;
    }
    .rows {
            display: flex;
            justify-content: center
        }
        .cols{
            width: 50%
        }
    @media (max-width: 600px) {
        #satu {
            background-image: url('/asset-lelang/detail_event.jpg');
            height: auto;
            background-position: left;
            background-size: cover;
            background-repeat: no-repeat;
            width: 100%;
            padding: 20px;
        }
/* 
        #satu h1,
        #satu h4 {
            display: none;
        } */

        #dua {
            background-image: url('asset-lelang/lelang2.jpg');
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            width: 100%;
            padding: 20px;
            color: white;
            text-align: center
        }
        .rows {
            display: block;
        }
        .cols{
            width: 100%;
        }
    }

</style>
<section id="satu">
    <div class="judul">
        <h1> DETAIL LOT {{$data->no_lot}}</h1>
    </div>
    <div class="row lelang">
        <div class=" bungkus">
            <div class="rows">
                <div class="cols">
                    <div class="card p-2" style="height: auto">
                        @if (count($data->barang_lelang->gambarlelang) > 0)
                        <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                            <div class="carousel-inner ">
                                @foreach ($data->barang_lelang->gambarlelang as $key => $item)
                                <div class="carousel-item {{$key == 0 ? 'active' : ''}}">
                                    <img src="{{ asset('storage/image/'.$item->gambar) }}" class="img-card-p2"
                                        alt="...">
                                </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev " type="button" data-bs-target="#carouselExampleFade"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon bg-dark" style="height: 100px; border-radius: 20px" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon bg-dark" style="height: 100px; border-radius: 20px" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>

                        @endif
                    </div>
                </div>
                <div class="cols">
                    <div class=" p-2 bungkus-deksripsi" style="height: auto">
                        <div class="row p-2">
                            @if ($data->barang_lelang->bahan_bakar == null)

                            <h5 class="card-text">Nama : {{$data->barang_lelang->barang}}</h5>
                            <h5 class="card-text">Brand : {{$data->barang_lelang->brand}}</h5>
                            <h5 class="card-text">Warna : {{$data->barang_lelang->warna}}</h5>
                            <h5 class="card-text">lokasi Barang : {{$data->barang_lelang->lokasi_barang}}</h5>
                            <h5 class="card-text">Keterangan : {!!$data->barang_lelang->keterangan!!}</h5>
                            @else

                            <div class="col-4">

                                <h5 class="card-text">Nama : {{$data->barang_lelang->barang}}</h5>
                                <h5 class="card-text">Brand : {{$data->barang_lelang->brand}}</h5>
                                <h5 class="card-text">Warna : {{$data->barang_lelang->warna}}</h5>
                                <h5 class="card-text">lokasi Barang : {{$data->barang_lelang->lokasi_barang}}</h5>
                                <h5 class="card-text">No Rangka : {{$data->barang_lelang->nomer_rangka}}</h5>
                                <h5 class="card-text">No Mesin : {{$data->barang_lelang->nomer_mesin}}</h5>
                                <h5 class="card-text">Tipe Kendaraan : {{$data->barang_lelang->tipe_mobil}}</h5>
                                <h5 class="card-text">Transisi Kendaraan : {{$data->barang_lelang->transisi_mobil}}</h5>
                            </div>
                            <div class="col-4">
                                <h5 class="card-text">Bahan Bakar : {{$data->barang_lelang->bahan_bakar}}</h5>
                                <h5 class="card-text">Odometer : {{$data->barang_lelang->odometer}}</h5>
                                <h5 class="card-text">Grade Utama : {{$data->barang_lelang->grade_utama}}</h5>
                                <h5 class="card-text">Grade Mesin : {{$data->barang_lelang->grade_mesin}}</h5>
                                <h5 class="card-text">Grade Interior : {{$data->barang_lelang->grade_interior}}</h5>
                                <h5 class="card-text">Grade Exterior : {{$data->barang_lelang->grade_exterior}}</h5>
                                <h5 class="card-text">No Polisi : {{$data->barang_lelang->no_polisi}}</h5>
                                <h5 class="card-text">STNK : {{$data->barang_lelang->stnk}}</h5>
                                <h5 class="card-text">STNK Berlaku : {{$data->barang_lelang->stnk_berlaku}}</h5>
                            </div>
                            <div class="col-4">
                                <h5 class="card-text">Tahun Produksi : {{$data->barang_lelang->tahun_produksi}}</h5>
                                @if ($data->barang_lelang->stnk == 'ada')
                                <h5 class="card-text">Stnk : <span class="badge bg-success">Ada</span></h5>
                                @else

                                <h5 class="card-text">Stnk : <span class="badge bg-danger">Tidak Ada</span></h5>
                                @endif
                                @if ($data->barang_lelang->bpkb == 'ada')
                                <h5 class="card-text">Bpkb : <span class="badge bg-success">Ada</span></h5>
                                @else

                                <h5 class="card-text">Bpkb : <span class="badge bg-danger">Tidak Ada</span></h5>
                                @endif

                                @if ($data->barang_lelang->faktur == 'ada')
                                <h5 class="card-text">Faktur : <span class="badge bg-success">Ada</span></h5>
                                @else
                                <h5 class="card-text">Faktur : <span class="badge bg-danger">Tidak Ada</span></h5>
                                @endif

                                @if ($data->barang_lelang->sph == 'ada')
                                <h5 class="card-text">SPH : <span class="badge bg-success">Ada</span></h5>
                                @else
                                <h5 class="card-text">SPH : <span class="badge bg-danger">Tidak Ada</span></h5>
                                @endif

                                @if ($data->barang_lelang->kir == 'ada')
                                <h5 class="card-text">KIR : <span class="badge bg-success">Ada</span></h5>
                                @else
                                <h5 class="card-text">KIR : <span class="badge bg-danger">Tidak Ada</span></h5>
                                @endif
                                @if ($data->barang_lelang->ktp == 'ada')
                                <h5 class="card-text">KTP Pemilik : <span class="badge bg-success">Ada</span></h5>
                                @else
                                <h5 class="card-text">KTP Pemilik : <span class="badge bg-danger">Tidak Ada</span></h5>
                                @endif

                                @if ($data->barang_lelang->kwitansi == 'ada')
                                <h5 class="card-text">Kwitansi : <span class="badge bg-success">Ada</span></h5>
                                @else
                                <h5 class="card-text">Kwitansi : <span class="badge bg-danger">Tidak Ada</span></h5>
                                @endif

                                <h5 class="card-text">Keterangan : {!!$data->barang_lelang->keterangan!!}</h5>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</section>

@endsection
