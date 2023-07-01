@extends('app.layouts')
@section('content')
<style>
</style>
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Detail Produk</h4>
        </div>
        <div class="card-body">
            <form>
                <div class="form-group">
                    <label>Toko</label>
                    <input type="text" class="form-control" value="{{ $data->toko->toko }}" readonly>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <br>
                    <input type="text" class="form-control" value="{{ $data->kategoriproduk->kategori }}" readonly>
                </div>
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" class="form-control" value="{{ $data->nama }}" readonly>
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea class="form-control" readonly>{{$data->keterangan}}</textarea>
                </div>
                <div class="form-group">
                    <label>Harga</label>
                    <br>
                    <input type="text" class="form-control" value="{{ 'Rp '. number_format($data->harga, 0, ',', '.') }}
                " readonly>
                </div>
                <div class="form-group">
                    <label>Stok</label>
                    <br>
                    <input type="text" class="form-control" value="{{ $data->stok }}" readonly>
                </div>
                <div class="form-group">
                    <label>Link Video</label>
                    <br>
                    <a href="{{ $data->video }}">{{ $data->video }}</a>
                </div>
                <div class="form-group">
                    <label>Cover Produk</label>
                    <br>
                    <img class="d-block" style="width:200px" src="{{ asset('storage/image/'.$data->thumbnail) }}" alt="">
                </div>
                    <div class="card-header">
                        <h4>Gambar Detail Produk</h4>
                    </div>
                    <div class="card-body">
                        <div id="carouselExampleIndicators3" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carouselExampleIndicators3" data-slide-to="0" class=""></li>
                                <li data-target="#carouselExampleIndicators3" data-slide-to="1" class=""></li>
                                <li data-target="#carouselExampleIndicators3" data-slide-to="2" class="active"></li>
                            </ol>
                            <div class="carousel-inner">
                                @foreach ($gambar as $key => $item)
                                <div class="carousel-item {{$key == 0 ?'active' : ''}}">
                                    <img class="d-block w-100" src="{{ asset('storage/image/'.$item->gambar) }}" alt="">
                                </div>
                                @endforeach
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleIndicators3" role="button"
                                data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators3" role="button"
                                data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>
@endsection
