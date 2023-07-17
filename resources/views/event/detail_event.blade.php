@extends('app.layouts')
@section('content')
<div class="card">
  <div class="card-header">
    <h4 class="w-100">Detail Event</h4>
</div>
    <div class="card-body">
        <div class="form-group">
          <label for="exampleInputEmail1">Gambar</label>
          <br>
          <img class="ms-auto" src="{{ asset('storage/image/'.$data->gambar) }}" style="width:700px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin:5px;">
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Judul</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $data->judul }}" readonly>
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Deskrispi</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $data->deskripsi }}" readonly>
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Link</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $data->link }}" readonly>
        </div>
    </div>
  </div>
@endsection
