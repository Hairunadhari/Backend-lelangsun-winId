@extends('app.layouts')
@section('content')
<div class="row ">
    <div class="col">
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
                            <label>Kecamatan / Kota / Provinsi / Kode Pos</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control"  placeholder="pilih berdasarkan kecamatan, kota, provinsi atau kode pos " aria-label="" id="input-alamat">
                                <div class="input-group-append">
                                  <button class="btn btn-primary" type="button" id="cari-alamat">Cari</button>
                                </div>
                              </div>
                            <select class="form-control" style="display: none" id="dropdown-alamat">
                               
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            <label>Email</label>
                            <input type="email" class="form-control" readonly value="{{$toko->user->email}}" >
                            @error('email')
                                <small class="text-danger">Email Sudah Terdaftar</small>
                            @enderror  
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6 col-12">
                            <input type="text" name="alamat" class="form-control" readonly id="val-alamat">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 col-12">
                            <label>Detail Alamat</label>
                           <textarea name="detail_alamat" id="" cols="30" rows="10" class="form-control" placeholder="contoh: Jl Melati No 2 RT01/RW03 Jakarta Selatan DKI Jakarta">{{$toko->detail_alamat}}</textarea>
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
                                <input type="file" name="logo" id="image-upload" accept=".jpg, .png, .jpeg">
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
<script>
     $(document).on('click', '#cari-alamat', function () {
            $('#cari-alamat').attr('disabled',true);
            value = $('#input-alamat').val();
            $.ajax({
                method: 'get',
                url: '/map/' + value,
                success: function (res) {
                    $('#dropdown-alamat').show();
                    $('#dropdown-alamat').html('<option value="" selected disabled>-- Pilih Alamat --</option>');
                    $.each(res.areas, function (index, area) {
                        console.log(area);
                        $('#dropdown-alamat').append('<option data-full="' + area
                            .name +
                            '">' + area.name + '</option>');
                            $('#cari-alamat').attr('disabled',false);
                    });
                },

                error: function (error) {
                    console.error('Error saat mengambil data:', error);
                }
            });
        });

        $('#dropdown-alamat').on('change', function () {
            value = $(this).val();
            // console.log(value);
            $('#dropdown-alamat').hide();
            $('#input-alamat').val(value);
            $('#val-alamat').val(value);
            
        })
</script>
@endsection