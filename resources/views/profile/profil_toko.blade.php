@extends('app.layouts')
@section('content')
<style>
    .border-green {
    border: 1px solid rgb(4, 247, 4);
    /* Properti CSS lainnya sesuai kebutuhan */
}

.border-red {
    border: 1px solid rgb(254, 48, 48);
    /* Properti CSS lainnya sesuai kebutuhan */
}

</style>
<div class="section-body">
    <div class="row mt-sm-4">
        <div class="col">
            <div class="card profile-widget">
                <div class="card-header">
                    <h4>Detail Profil</h4>
                    @if ($getCitybyToko->city_name == null || $getProvinsibyToko->provinsi == null)
                    <span class="badge badge-danger">profil anda belum lengkap</span>
                    @else
                    <span class="badge badge-success">profil anda sudah lengkap</span>
                            
                        @endif
                </div>
                <div class="profile-widget-header mt-1 text-center">
                    <img alt="image" src="{{ asset('storage/image/'.$toko->logo) }}" class="w-50 me-5"
                        style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
                </div>
                <div class="profile-widget-description ">
                    <div class="form-group">
                        <label>Nama Toko</label>
                        <input type="text" class="form-control <?php echo ($toko->toko !== null) ? 'border-green' : 'border-red'; ?>" value="{{ $toko->toko }}" readonly>
                        
                    </div>
                    <div class="form-group">
                        <label>Nama Pemilik</label>
                        <input type="text" class="form-control <?php echo ($toko->user->name !== null) ? 'border-green' : 'border-red'; ?>" value="{{ $toko->user->name }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Provinsi</label>
                        <input type="text" class="form-control <?php echo ($getProvinsibyToko->provinsi !== null) ? 'border-green' : 'border-red'; ?>" value="{{ $getProvinsibyToko->provinsi }}" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Kota/Kabupaten</label>
                        <input type="text" class="form-control <?php echo ($getCitybyToko->city_name !== null) ? 'border-green' : 'border-red'; ?>" value="{{ $getCitybyToko->city_name }}" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-lg-7">
            <div class="card">
                <form method="post" action="{{route('admin.update-akun-toko', $toko->user->id)}}" class="needs-validation"
                    enctype="multipart/form-data" novalidate="">
                    @csrf
                    @method('PUT')
                    <div class="card-header">
                        <h4>Edit Profil</h4>
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
                            <div class="form-group col-md-6 col-12">
                                <label>Provinsi</label>
                                <select class="form-control select2" name="provinsi_id" id="provinsi_id">
                                    @foreach ($provinsi as $item)
                                    <option class="" value="{{$item->id}}">{{$item->provinsi}}</option>
                                    @endforeach
                                </select>
                                @error('provinsi_id')
                                <small class="text-danger">Masukan Nama Provinsi</small>
                            @enderror  
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label>Kota/Kabupaten</label>
                                <select class="form-control select2" name="city_id" id="city_id">
                                </select>
                                @error('city_id')
                                <small class="text-danger">Masukan Nama Kota/Kabupaten</small>
                            @enderror  
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
<script>
       $(document).ready(function () {
            $('#provinsi_id').on('change', function () {
                var provinceId = this.value;
                $('#city_id').html('');
                $.ajax({
                    url: "/get-city/" + provinceId,
                    type: 'get',
                    success: function (res) {
                    //    console.log(res);
                       $('#city_id').html('<option value="" selected disabled>-- Pilih Kategori --</option>');
                        $.each(res, function (key, value) {
                            $('#city_id').append('<option value="' + value
                                .id + '">' + value.city_name + '</option>');
                        });
                    }
                });
            });
        });
</script>
@endsection
