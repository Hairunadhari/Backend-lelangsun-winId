@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="w-100">Input UserCMS</h4>
                </div>
                <div class="card-body">
                    <form action="{{route('add-user')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Nama<span style="color: red">*</span></label>
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}"
                                    autofocus required>
                                @if ($errors->has('name'))
                                <small class="text-danger">Max 280 karakter!</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Role <span style="color: red">*</span></label>
                                <select class="form-control selectric" name="role_id" required>
                                    @foreach ($role as $item)
                                    <option value="{{ $item->id }}">{{ $item->role }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="email">Email <span style="color: red">*</span></label>
                                <input id="email" type="email" class="form-control" name="email" required>
                                @if ($errors->has('email'))
                                <small class="text-danger">Email Sudah Terdaftar!</small>
                                @endif
                            </div>
                            <div class=" form-group">
                                <label for="password" class="d-block">Password <span style="color: red">*</span></label>
                                <input id="password" type="password" class="form-control" name="password">
                                @if ($errors->has('password'))
                                <small class="text-danger">Password harus memiliki 10 karakter!</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation" class="d-block">Password Confirmation <span
                                        style="color: red">*</span></label>
                                <input id="password_confirmation" type="password" class="form-control"
                                    name="password_confirmation" required>
                                @if ($errors->has('password_confirmation'))
                                <small class="text-danger">Konfirmasi Password Tidak Cocok!</small>
                                @endif
                            </div>
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
