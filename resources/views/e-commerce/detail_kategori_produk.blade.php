@extends('app.layouts')
@section('content')
<div class="section-header">
  <h1>Data E-commerce</h1>
</div>
<div class="card m-auto" style="width: 50rem;">
    <div class="card-body">
      <form>
        <div class="form-group">
          <label for="exampleInputEmail1">Kategori:</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $data->kategori }}">
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Gambar:</label>
          <br>
          <img src="{{ asset('storage/image/'.$data->gambar) }}" width="150">
        </div>
      </form>
    </div>
  </div>
@endsection
