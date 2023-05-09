@extends('app.layouts')
@section('content')
<div class="section-header">
  <h1>Data Lelang</h1>
</div>
<div class="card m-auto" style="width: 50rem;">
    <div class="card-body">
      <form>
        <div class="form-group">
          <label for="exampleInputEmail1">Kategori:</label>
          <input type="text" class="form-control" id="exampleInputEmail1" value="{{ $data->kategori }}" readonly>
        </div>
      </form>
    </div>
  </div>
@endsection
