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
        grid-gap: 20px; /* Spasi antara card */
        padding: 0 20px; /* Spasi kiri dan kanan dari container */
        justify-content: center;
    }
    #satu .items {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(13rem, 1fr));
        grid-gap: 0px; /* Spasi antara card */
        padding: 0px; /* Spasi kiri dan kanan dari container */
        justify-content: center;
    }
    #satu .bungkus-lot img{
        height: 200px;
    }
    .item-barang img{
        width: 200px;
        height: 200px;
    }
    .items img{
        box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin:5px; padding:0.25rem; border:1px solid #dee2e6; 
    }

    .img-card-p2{
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    h5{
        box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin:5px;
        padding: 5px;
    }
    @media (max-width: 600px) {
        #satu {
            background-image: url('detail_event.jpg');
            height: auto;
            background-position: left;
            background-size: cover;
            background-repeat: no-repeat;
            width: 100%;
            padding: 20px;
        }
        #satu h1,#satu h4{
            display: none;
        }
        #dua{
            background-image: url('asset-lelang/lelang2.jpg');
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            width: 100%;
            padding: 20px;
            color: white;
            text-align: center
        }
    }
</style>
    <section id="satu">
        <div class="judul">
            <h1> DETAIL LOT {{$data->id}}</h1>
        </div>
        <div class="row lelang">
            <div class=" bungkus">
                <div class="row d-flex">
                        <div class="col-6">
                            <div class="card p-2" style="height: auto">
                                <img src="{{ asset('storage/image/'.$data->barang_lelang->gambarlelang[0]->gambar) }}"  alt="..." class="img-card-p2">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card p-2" style="height: auto">
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
                                            <h5 class="card-text">Bpkb : {{$data->barang_lelang->bpkb}}</h5>
                                            <h5 class="card-text">Faktur : {{$data->barang_lelang->faktur}}</h5>
                                            <h5 class="card-text">SPH : {{$data->barang_lelang->sph}}</h5>
                                            <h5 class="card-text">KIR : {{$data->barang_lelang->kir}}</h5>
                                            <h5 class="card-text">KTP Pemilik : {{$data->barang_lelang->ktp}}</h5>
                                            <h5 class="card-text">Kwitansi : {{$data->barang_lelang->kwitansi}}</h5>
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