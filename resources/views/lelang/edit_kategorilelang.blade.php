@extends('app.layouts')
@section('content')
<!-- Begin Page Content -->
<div class="section-header">
    <h1>Data Lelang</h1>
</div>
<div class="section-body">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Edit Kategori</h4>
            </div>
            <form action="{{route('update-kategori-lelang', $data->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    @if($errors->has('kategori'))
                    <div class="alert alert-danger alert-dismissible text-center fade show" role="alert">
                        <strong>{{ $errors->first('kategori') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <div class="form-group">
                        <label>Kategori</label>
                        <input type="text" class="form-control" value="{{ old('toko', $data->kategori) }}" name="kategori">
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary mr-1" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
