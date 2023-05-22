@extends('app.layouts')
@section('content')
<style>
</style>
<div class="section-header">
    <h1>Data Lelang</h1>
</div>
<div class="section-body">
    <div class="container-fluid">
        <form action="{{route('update-barang-lelang', $data->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="card">
                <div class="card-header">
                    <h4>Edit Barang Lelang</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Pilih Kategori Barang Lelang</label>
                        <select class="form-control" name="kategoribarang_id" >
                            @foreach ($kategori as $item)
                            <option value="{{ $item->id }}" {{ $item->id == $data->kategoribarang->id ? 'selected' : '' }}>
                                {{ $item->kategori }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" class="form-control" name="barang" value="{{ $data->barang }}" >
                    </div>
                    <div class="form-group">
                        <label>Nama Pemilik Barang Lelang</label>
                        <input type="text" class="form-control" name="nama_pemilik" value="{{ $data->nama_pemilik }}" >
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea class="form-control" name="keterangan" >{{$data->keterangan}}</textarea>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary mr-1" type="submit">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
