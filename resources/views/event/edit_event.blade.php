@extends('app.layouts')
@section('content')
<style>
    #preview img{
            box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
    }
</style>
    <div class="card">
        <div class="card-header">
            <h4>Edit Event</h4>
        </div>
        <form action="{{route('update-event', $data->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label>Ganti Gambar</label>
                    <input type="file" class="form-control mb-4" name="gambar" id="gambar">
                    <div id="preview" class="mb-3"></div>
                </div>
                <div class="form-group">
                    <label>Judul</label>
                    <input type="text" class="form-control" value="{{ old('judul', $data->judul) }}" name="judul">
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <input type="text" class="form-control" value="{{ old('deskripsi', $data->deskripsi) }}" name="deskripsi">
                </div>
                <div class="form-group">
                    <label>Link</label>
                    <input type="text" class="form-control" value="{{ old('link', $data->link) }}" name="link">
                </div>
            </div>
            <div class="card-footer text-right">
                <button class="btn btn-primary mr-1" type="submit">Submit</button>
            </div>
        </form>
    </div>
<script>
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
