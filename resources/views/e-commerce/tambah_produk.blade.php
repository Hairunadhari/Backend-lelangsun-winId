@extends('app.layouts')
@section('content')
<style>
    .review img {
        margin-bottom: 20px;
        margin-left: 20px;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
    }

</style>
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Form Tambah Produk</h4>
        </div>
        <div class="card-body">
            <form action="{{route('addproduk')}}" method="post" enctype="multipart/form-data">
                @csrf
                @if (Auth::user()->role->role == 'Super Admin')
                    <div class="form-group">
                        <label>Toko <span style="color: red">*</span></label>
                        <select class="form-control select2" id="tokos" name="toko_id">
                            <option selected disabled>-- Pilih Toko --</option>
                            @foreach ($toko as $item)
                            <option value="{{ $item->id }}">{{ $item->toko }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kategori <span style="color: red">*</span></label>
                        <select class="form-control select2" id="kategoris" name="kategoriproduk_id" required>
                        </select>
                        @if ($errors->has('kategoriproduk_id'))
                        <small class="text-danger">Kategori tidak boleh kosong!</small>
                        @endif
                    </div>
                @else
                    <div class="form-group" hidden>
                        <input type="text" name="toko_id" value="{{$idToko->id}}">
                    </div>
                    <div class="form-group">
                        <label>Kategori <span style="color: red">*</span></label>
                        <select class="form-control select2" name="kategoriproduk_id">
                            @foreach ($kategori_bedasarkan_toko as $item)
                            <option value="{{ $item->id }}">{{ $item->kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                    <div class="form-group">
                        <label>Nama Produk <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label>Keterangan <span style="color: red">*</span></label>
                        <textarea class="form-control" name="keterangan" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Harga <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="harga" onkeyup="formatNumber(this)" required>
                    </div>
                    <div class="form-group">
                        <label>Stok <span style="color: red">*</span></label>
                        <input type="number" class="form-control" name="stok" required onkeyup="formatStok(this)">
                    </div>
                    <div class="form-group" id="inputberat">
                        <label>Berat (gram) <span style="color: red">*</span></label>
                        <input type="number" class="form-control" name="berat" required  onkeyup="formatStok(this)">
                    </div>

                    <div class="form-group">
                        <label>Gambar Produk <small>(bisa pilih lebih dari satu gambar) </small><span
                                style="color: red">*</span></label>
                                <div class="input-images"></div>
                    </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
      $('.input-images').imageUploader({
        imagesInputName: 'gambar',
        maxSize: 200 * 1024, // maksimum ukuran dalam byte (200 KB)
         maxFiles: 4, 
    });
    $(document).ready(function () {
            $('#tokos').on('change', function () {
                var tokosId = this.value;
                $('#kategoris').html('');
                $.ajax({
                    url: "/superadmin/get-kategori-by-toko/"+tokosId,
                    type: 'get',
                    success: function (res) {
                        $('#kategoris').html('<option value="" selected disabled>-- Pilih Kategori --</option>');
                        $.each(res, function (key, value) {
                            console.log(value);
                            $('#kategoris').append('<option value="' + value
                                .id + '">' + value.kategori + '</option>');
                        });
                    }
                });
            });

         
        });

    


    function formatNumber(input) {
        // Menghilangkan karakter selain angka
        var num = input.value.replace(/[^0-9]/g, '');

        // Memformat angka menjadi format ribuan dan desimal
        var formattedNum = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(num);

        // Memasukkan nilai format ke dalam input
        input.value = formattedNum;
    }

    function formatStok(input) {
        // Menghilangkan karakter selain angka
        var num = input.value.replace(/[^0-9]/g, '');

        input.value = num;
    }


</script>

@endsection
