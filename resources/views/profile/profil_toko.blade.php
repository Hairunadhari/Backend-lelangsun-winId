@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row mt-sm-4">
        <div class="col">
            <div class="card profile-widget">
                <div class="profile-widget-header mt-5 text-center">
                    <img alt="image" src="{{ asset('storage/image/'.$toko->logo) }}" class="w-50 me-5"
                        style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
                </div>
                <div class="profile-widget-description text-center">
                    <div class="profile-widget-name">{{$toko->toko}}<div
                            class="text-muted d-inline font-weight-normal">
                            <div class="slash"></div>{{$toko->user->name}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-lg-7">
            <div class="card">
                <form method="post" action="{{route('update-akun-toko', $toko->user->id)}}" class="needs-validation"
                    enctype="multipart/form-data" novalidate="">
                    @csrf
                    @method('PUT')
                    <div class="card-header">
                        <h4>Edit Profile</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6 col-12">
                                <label>Nama Pemilik</label>
                                <input type="text" class="form-control" name="name" value="{{$toko->user->name}}" required="">
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label>Nama Toko</label>
                                <input type="text" class="form-control" name="toko" value="{{$toko->toko}}" required="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 col-12">
                                <label>Email</label>
                                <input type="email" class="form-control" readonly value="{{$toko->user->email}}" >
                                @error('email')
                                    <small class="text-danger">Email Sudah Terdaftar</small>
                                @enderror  
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label>Password<small>(min 10 karakter)</small></label>
                                <input type="password" class="form-control" name="password">
                                @error('password')
                                    <small class="text-danger">password min 10 karakter</small>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label for="password_confirmation" class="d-block">Password Confirmation</label>
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation">
                                @error('password_confirmation')
                                    <small class="text-danger">password tidak cocok</small>
                                @enderror
                              </div>
                        </div>
                        <div class="form-group">
                            <label class="">Logo Toko</label>
                            <div class="col-sm-12 col-md-7">
                                <div id="image-preview" class="image-preview">
                                    <label for="image-upload" id="image-label">Choose File</label>
                                    <input type="file" name="logo" id="image-upload">
                                </div>
                            </div>
                            @error('logo')
                                <small class="text-danger">format jpeg,jpg,png. max: 2048 kb</small>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
