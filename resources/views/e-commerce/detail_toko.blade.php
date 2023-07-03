@extends('app.layouts')
@section('content')
<div class="card">
  <div class="card-header">
    <h4 class="w-100">Detail Toko</h4>
</div>
    <div class="card-body">
      <form>
        <div class="form-group">
          <label for="exampleInputEmail1">Nama Toko:</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $data->toko }}" readonly>
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Logo:</label>
          <br>
          <img class="ms-auto" src="{{ asset('storage/image/'.$data->logo) }}" style="width:700px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin:5px;">
        </div>
      </form>
    </div>
  </div>
@endsection
