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
                        <label>Kategori Barang Lelang</label>
                        <br>
                        <input type="text" class="form-control" value="{{ $data->kategoribarang->kategori }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nama Barang Lelang</label>
                        <input type="text" class="form-control" value="{{ $data->barang }}" readonly>
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection
