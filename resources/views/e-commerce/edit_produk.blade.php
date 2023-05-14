@extends('app.layouts')
@section('content')
<style>
    #preview img, .form-group img {
        margin-bottom: 20px;
        margin-left: 20px;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
    }

</style>
<!-- Begin Page Content -->
<div class="section-header">
    <h1>Data E-commerce</h1>
</div>
<div class="section-body">
    <div class="container-fluid">
        <form action="{{route('updateproduk', $data->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">
                    <h4>Edit Produk</h4>
                </div>
                <div class="card-body">
                    @if ($errors->has('kategori'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Kategori sudah terdaftar!</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <div class="form-group">
                        <label>Nama Toko:</label>
                        <select class="form-control" name="toko_id" required>
                            @foreach ($dataToko as $item)
                            <option value="{{ $item->id }}" {{ $item->id == $data->toko->id ? 'selected' : '' }}>
                                {{ $item->toko }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kategori Produk:</label>
                        <select class="form-control" name="kategoriproduk_id" required>
                            @foreach ($dataKategoriproduk as $item)
                            <option value="{{ $item->id }}"
                                {{ $item->id == $data->kategoriproduk->id ? 'selected' : '' }}>
                                {{ $item->kategori }}
                            </option>
                            @endforeach
                        </select>
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
                        <label for="exampleInputEmail1">Link Video:</label>
                        <input type="text" class="form-control" name="video" value="{{$data->video}}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Cover Produk:</label>
                        <input type="file" class="form-control" name="thumbnail" value="{{$data->thumbnail}}">
                    </div>
                    <div class="form-group" >
                        <label for="">Gambar Detail Produk:</label>
                        <br>
                        @foreach ($gambar as $item)
                        <img class="ms-auto" src="{{ asset('storage/image/'.$item->gambar) }}"
                            style="width:200px;box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;">
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
</div>
<script>
    function previewImages() {
        var preview = document.querySelector('#preview');
        if (this.files) {
            [].forEach.call(this.files, readAndPreview);
        }

        function readAndPreview(file) {
            if (!/\.(jpe?g|png|gif|webp)$/i.test(file.name)) {
                return alert(file.name + " is not an image");
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

    document.querySelector('#resetButton').addEventListener('click', function () {
        document.querySelector('#preview').innerHTML = '';
    });

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
