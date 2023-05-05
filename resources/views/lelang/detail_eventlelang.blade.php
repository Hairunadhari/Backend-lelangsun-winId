@extends('app.layouts')
@section('content')
<div class="section-header">
    <h1>Data E-commerce</h1>
</div>
<div class="card m-auto" style="width: 50rem;">
    <div class="card-body">
      <form>
        <div class="form-group">
          <label>Nama Event:</label>
          <input type="text" class="form-control" value="{{ $data->event }}" readonly>
        </div>
      </form>
    </div>
  </div>
@endsection
