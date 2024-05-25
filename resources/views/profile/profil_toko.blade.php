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
    <div class="row">
        <div class="col">
            <div class="card ">
                <div class="card-header">
                    <div class="w-100">

                        <h4>Profil Toko</h4>
                        <span>Status :</span>
                        @if ($toko->postal_code == null || $toko->detail_alamat == null)
                        <span class="badge badge-danger">profil toko belum lengkap</span>
                        @else
                        <span class="badge badge-success">profil toko sudah lengkap</span>
                        
                        @endif
                    </div>
                        <a href="{{route('admin.formedit-akun-toko', $encryptId)}}" class="btn btn-primary">Edit Profil</a>
                </div>
                <div class="row">
                    <div class="col-4">
                            <div class="card-body">
                                <div class="profile-widget-header mt-1 text-center">
                                    <img alt="image" src="{{ asset('storage/image/'.$toko->logo) }}" 
                                        style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px; width: 320px">
                                </div>
                            </div>
                    </div>
                    <div class="col-8">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Nama Toko</label>
                                    <input type="text" class="form-control <?php echo ($toko->toko !== null) ? 'border-green' : 'border-red'; ?>" value="{{ $toko->toko }}" readonly>
                                    
                                </div>
                                <div class="form-group">
                                    <label>Nama Pemilik</label>
                                    <input type="text" class="form-control <?php echo ($toko->user->name !== null) ? 'border-green' : 'border-red'; ?>" value="{{ $toko->user->name }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Kecamatan / Kota / Provinsi / Kode Pos</label>
                                    <input type="text" class="form-control <?php echo ($toko->postal_code !== null) ? 'border-green' : 'border-red'; ?>" value="{{$toko->kecamatan}}, {{$toko->kota}}, {{$toko->provinsi}} {{ $toko->postal_code }} " readonly>
                                </div>
                                
                                <div class="form-group">
                                    <label>Detail Alamat</label>
                                    <input type="text" class="form-control <?php echo ($toko->detail_alamat !== null) ? 'border-green' : 'border-red'; ?>" value="{{ $toko->detail_alamat }}" readonly>
                                </div>
                            </div>
                    </div>
                </div>
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
