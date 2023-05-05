@extends('app.layouts')
@section('content')
<!-- Begin Page Content -->
<div class="section-header">
    <h1>Data E-commerce</h1>
</div>
<div class="section-body">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Edit Event</h4>
            </div>
            <form action="{{route('update-event-lelang', $data->id)}}" method="post">
                @csrf
                @method('PUT')
                <div class="card-body">
                    @if ($errors->has('event'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Event sudah terdaftar!</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                    </div>
                    @endif
                    <div class="form-group">
                        <label>Nama Event</label>
                        <input type="text" class="form-control" value="{{ old('event', $data->event) }}" name="event">
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
