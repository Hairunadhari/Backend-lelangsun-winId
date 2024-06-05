@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="w-100">Input Toko & Admin</h4>
                </div>
                <div class="card-body">
                    <form action="{{route('superadmin.add-admin')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nama Toko <span style="color: red">*</span></label>
                                <input type="text" class="form-control" name="toko" value="{{ session('toko') }}"
                                    autofocus required>
                                   
                            </div>
                            <div class="form-group">
                                <label class="">Logo <span style="color: red">*</span></label>
                                <div class="col-sm-12 col-md-7">
                                    <div id="image-preview" class="image-preview">
                                        <label for="image-upload" id="image-label">Choose File</label>
                                        <input type="file" accept=".jpg, .png, .jpeg" name="logo" id="image-upload" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name">Nama Pemilik<span style="color: red">*</span></label>
                                <input id="name" type="text" class="form-control" name="name" value="{{ session('name') }}" autofocus required>
                                
                            </div>
                            <div class="form-group">
                                <label for="name">No Telephone<span style="color: red">*</span></label>
                                <input type="text" class="form-control" name="no_telp" value="{{ session('no_telp') }}" autofocus required>
                                
                            </div>
                                <div class="form-group">
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
                                    <div class="form-group">
                                        <input type="text" name="alamat" class="form-control" readonly id="val-alamat">
                                    </div>
                                    <div class="form-group">
                                        <label>Detail Alamat</label>
                                       <textarea name="detail_alamat" id="" cols="30" rows="10" class="form-control" placeholder="contoh: Jl Melati No 2 RT01/RW03 Jakarta Selatan DKI Jakarta"></textarea>
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
                                <input id="email" type="email" class="form-control" name="email" required value="{{session('email')}}">
                                
                            </div>
                            <div class=" form-group">
                                <label for="password" class="d-block">Password <span style="color: red">*</span></label>
                                <input id="password" type="password" class="form-control" name="password" required>
                               
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation" class="d-block">Password Confirmation <span
                                        style="color: red">*</span></label>
                                <input id="password_confirmation" type="password" class="form-control"
                                    name="password_confirmation" required>
                                
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
<!-- Modal -->


<!-- /.container-fluid -->
