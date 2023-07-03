@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Edit Kategori</h4>
            </div>
            <form action="{{route('update-kategori-produk', $data->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    @if($errors->has('kategori'))
                    <div class="alert alert-danger alert-dismissible text-center fade show" role="alert">
                        <strong>{{ $errors->first('kategori') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <div class="form-group">
                        <label>Kategori</label>
                        <input type="text" class="form-control" value="{{ old('toko', $data->kategori) }}" name="kategori">
                    </div>
                    <div class="form-group">
                        <label>Gambar:</label>
                        <br>
                        <img src="{{ asset('storage/image/'.$data->gambar) }}" width="150">
                    </div>
                    <div class="form-group">
                        <label>Ganti Gambar</label>
                        <input type="file" class="form-control mb-2" name="gambar" id="gambar">
                        <div id="preview"></div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary mr-1" type="submit">Submit</button>
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
    
    document.querySelector('#resetButton').addEventListener('click', function() {
        document.querySelector('#preview').innerHTML = '';
    });
</script>
@endsection
