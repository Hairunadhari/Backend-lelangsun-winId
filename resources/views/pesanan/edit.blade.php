@extends('app.layouts')
@section('content')
<div class="section-body">
    <form action="{{route('update-pesanan', $data->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h4>Edit Order {{$data->no_invoice}}</h4>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>No Resi: </label>
                    <input type="text" class="form-control"  name="no_resi">
                </div>
            </div>
            <div class="card-footer text-right">
                <button class="btn btn-success mr-1" type="submit">Simpan</button>
            </div>
        </div>
    </form>
</div>
@endsection
