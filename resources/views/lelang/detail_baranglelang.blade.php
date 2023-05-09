@extends('app.layouts')
@section('content')
<style>
</style>
<div class="section-header">
    <h1>Data Lelang</h1>
</div>
<div class="section-body">
    <div class="container-fluid">
        <form>
            <div class="card">
                <div class="card-header">
                    <h4>Detail Barang Lelang</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Kategori</label>
                        <br>
                        <input type="text" class="form-control" value="{{ $data->kategoribarang->kategori }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nama Barang Lelang</label>
                        <input type="text" class="form-control" value="{{ $data->barang }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nama Pemilik Barang Lelang</label>
                        <input type="text" class="form-control" value="{{ $data->nama_pemilik }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea class="form-control" readonly>{{$data->keterangan}}</textarea>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Lampiran Faktur/Ktp/Kwitansi</h4>
                </div>
                <div class="card-body">
                    <div class="gallery">
                        <div class="gallery-item" data-image="{{ asset('storage/image/'.$data->faktur) }}" data-title="Image 1">
                        </div>
                        <div class="gallery-item" data-image="{{ asset('storage/image/'.$data->ktp) }}" data-title="Image 2">
                        </div>
                        <div class="gallery-item" data-image="{{ asset('storage/image/'.$data->kwitansi) }}"
                            data-title="Image 3">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection
