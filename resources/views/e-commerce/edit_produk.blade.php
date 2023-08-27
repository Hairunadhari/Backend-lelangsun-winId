@extends('app.layouts')
@section('content')
<style>
    #preview img, .form-group img {
        margin-bottom: 20px;
        margin-left: 20px;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
    }

</style>
<div class="section-body">
        <form action="{{route('updateproduk', $data->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">
                    <h4>Edit Produk</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Nama Toko:</label>
                        <select class="form-control select2" name="toko_id" id="tokos" required>
                             @foreach ($toko as $item)
                            <option value="{{ $item->id }}"
                                {{ $item->id == $data->toko->id ? 'selected' : '' }}>
                                {{ $item->toko }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kategori Produk:</label>
                        <select class="form-control select2" name="kategoriproduk_id" id="kategoris" required>
                            @foreach ($kategori as $item)
                            <option value="{{ $item->id }}"
                                {{ $item->id == $data->kategoriproduk->id ? 'selected' : '' }}>
                                {{ $item->kategori }}
                            </option>
                            @endforeach
                        </select>
                        @if ($errors->has('kategoriproduk_id'))
                    <small class="text-danger">Kategori tidak boleh kosong!</small>
                    @endif
                    </div>
                    <div class="form-group">
                        <label>Nama Produk: </label>
                        <input type="text" class="form-control" value="{{ old('nama', $data->nama) }}" name="nama">
                    </div>
                    <div class="form-group">
                        <label>Keterangan:</label>
                        <textarea class="form-control" name="keterangan">{{$data->keterangan}}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Harga: </label>
                        <input type="text" class="form-control" value="{{ old('harga', $data->harga) }}" name="harga" onkeyup="formatNumber(this)">
                    </div>
                    <div class="form-group">
                        <label>Stok: </label>
                        <input type="number" class="form-control" value="{{ old('stok', $data->stok) }}" name="stok">
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Media</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Link Video: <small>(isi - (strip) jika tidak punya link video)</small></label>
                        <input type="text" class="form-control" name="video" value="{{$data->video}}">
                    </div>
                    <div class="form-group">
                        <label class="">Cover Produk</label>
                        <div class="col-sm-12 col-md-7">
                            <div id="image-preview" class="image-preview">
                                <label for="image-upload" id="image-label">Choose File</label>
                                <input type="file" name="thumbnail" id="image-upload" value="{{$data->thumbnail}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group" >
                        <label for="">Gambar Detail Produk:</label>
                        <br>
                        @foreach ($gambar as $item)
                        <img class="ms-auto" src="{{ asset('storage/image/'.$item->gambar) }}"
                            style="width:100px;box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;">
                        @endforeach
                    </div>
                    <div class="form-group gambar">
                        <label>Ganti Gambar Detail Produk <small>(bisa pilih lebih dari satu gambar)</small></label>
                        <br>
                        <input type="file" name="gambar[]" class="form-control mb-2" id="gambar" multiple>
                        <div id="preview"></div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary mr-1" type="submit">Simpan</button>
                </div>
        </form>
</div>
</div>
<script>
        $(document).ready(function () {
            $('#tokos').on('change', function () {
                var tokosId = this.value;
                $('#kategoris').html('');
                $.ajax({
                    url: "get-kategori-by-toko/"+tokosId,
                    type: 'get',
                    success: function (res) {
                        console.log(res);
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
   function previewImages() {
        var preview = document.querySelector('#preview');

        // Hapus semua elemen child di dalam elemen #preview
        while (preview.firstChild) {
            preview.removeChild(preview.firstChild);
        }

        if (this.files) {
            [].forEach.call(this.files, readAndPreview);
        }

        function readAndPreview(file) {
            if (!/\.(jpe?g|png)$/i.test(file.name)) {
                alert(file.name + " format tidak sesuai");
                document.querySelector('#gambar').value = '';
                preview.removeChild(preview.firstChild);
                return;
            }
            var reader = new FileReader();
            reader.addEventListener("load", function () {
                var image = new Image();
                image.width = 200;
                image.title = file.name;
                image.src = this.result;
                preview.appendChild(image);
            }, false);
            reader.readAsDataURL(file);
        }
    }

    document.querySelector('#gambar').addEventListener("change", previewImages);

    function formatNumber(input) {
      // Menghilangkan karakter selain angka
      var num = input.value.replace(/[^0-9]/g, '');
      
      // Memformat angka menjadi format ribuan dan desimal
      var formattedNum = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);
    
      // Memasukkan nilai format ke dalam input
      input.value = formattedNum;
    }

</script>
@endsection
