@extends('app.layouts')
@section('content')
<section class="section">
    <div class="section-body">
        <div class="row mt-sm-4">
            <div class="col-12 col-md-12 col-lg-5">
                <div class="card profile-widget">
                    <div class="profile-widget-header">
                        <img alt="image" src="{{ asset('storage/image/'.$data->foto) }}"
                            class="rounded-circle profile-widget-picture">
                    </div>
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible text-center fade show" role="alert">
                        <strong>{{ session('success') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <div class="profile-widget-description">
                        <div class="form-group">
                            <label>Nama:</label>
                            <input type="text" class="form-control" value="{{ $data->name }}" name="toko" readonly>
                        </div>
                        <div class="form-group">
                            <label>Role:</label>
                            <input type="text" class="form-control" value="Super Admin" name="toko" readonly>
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="text" class="form-control" value="{{ old('toko', $data->email) }}" name="toko"
                                readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-7">
                <div class="card">
                    <form method="post" action="{{route('update-akun', $data->id)}}" class="needs-validation" enctype="multipart/form-data"
                        novalidate="">
                        @csrf
                        @method('PUT')
                        <div class="card-header">
                            <h4>Edit Profil</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" value="{{$data->name}}" name="name">
                            </div>
                            <div class="form-group">
                                <label class="">Foto</label>
                                <div class="col-sm-12 col-md-7">
                                    <div id="image-preview" class="image-preview">
                                        <label for="image-upload" id="image-label">Choose File</label>
                                        <input type="file" name="foto" id="image-upload">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary"type="submit">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection