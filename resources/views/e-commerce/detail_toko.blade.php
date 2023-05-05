@extends('app.layouts')
@section('content')
<div class="section-header">
  <h1>Data E-commerce</h1>
</div>
<div class="card m-auto" style="width: 50rem;">
    <div class="card-body">
      <form>
        <div class="form-group">
          <label for="exampleInputEmail1">Nama Toko:</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $data->toko }}" readonly>
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Logo:</label>
          <br>
          <img class="ms-auto" src="{{ asset('storage/image/'.$data->logo) }}" style="width:700px">
        </div>
      </form>
    </div>
  </div>
@endsection
