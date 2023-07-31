@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="w-100">Edit UserCMS</h4>
                </div>
                <div class="card-body">
                    <form action="{{route('update-user', $data->id)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Name </label>
                                <input id="name" type="text" class="form-control" name="name" value="{{ $data->name}}"  >
                            </div>
                            <div class="form-group">
                                <label>Role </label>
                                <select class="form-control selectric" name="role_id" >
                                    @foreach ($role as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $item->id == $data->role_id ? 'selected' : '' }}>
                                        {{ $item->role }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="email">Email </label>
                                <input id="email" type="email" class="form-control" name="email" value="{{$data->email}}">
                            @if ($errors->has('email'))
                                <small class="text-danger">Email Sudah Terdaftar!</small>
                                @endif
                    </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<!-- Modal -->


<!-- /.container-fluid -->
