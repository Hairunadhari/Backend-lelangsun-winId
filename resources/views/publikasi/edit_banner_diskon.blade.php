@extends('app.layouts')
@section('content')
<style>
    #preview img{
            box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
    }
</style>
    <div class="card">
        <div class="card-header">
            <h4>Edit Banner</h4>
        </div>
        <form action="{{route('update-banner-diskon', $data->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label>Gambar:</label>
                    <br>
                    <img src="{{ asset('storage/image/'.$data->gambar) }}" width="150" style=" box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;">
                </div>
                <div class="form-group">
                    <label>Ganti Gambar <small>(png, jpg, jpeg)</small></label>
                    <input type="file" class="form-control mb-4" name="gambar" id="gambar">
                    <div id="preview"></div>
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
    
    document.querySelector('#resetButton').addEventListener('click', function() {
        document.querySelector('#preview').innerHTML = '';
    });
</script>
@endsection
