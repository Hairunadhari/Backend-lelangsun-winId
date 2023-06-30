@extends('app.layouts')
@section('content')
<div class="card">
  <div class="card-header">
    <h4 class="w-100">Detail Kategori Produk</h4>
</div>
    <div class="card-body">
      <form>
        <div class="form-group">
          <label for="exampleInputEmail1">Kategori:</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $data->kategori }}" readonly>
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Gambar:</label>
          <br>
          <img src="{{ asset('storage/image/'.$data->gambar) }}"  style="width:700px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin:5px;">
        </div>
      </form>
    </div>
  </div>
@endsection
