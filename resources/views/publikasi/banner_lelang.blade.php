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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Banner Web</h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                aria-controls="home" aria-selected="true">Banner</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <form action="{{route('superadmin.update-banner-web',$data->id)}}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <label>Judul <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" name="judul" value="{{$data->judul}}">
                                </div>
                                <div class="form-group">
                                    <label>Deskripsi <span style="color: red">*</span></label>
                                        <textarea class="summernote-simple" placeholder="keterangan..."
                                        name="deskripsi">{{$data->deskripsi}}</textarea>
                                </div>
                                <div class="form-group" >
                                    <label for="">Banner Web:</label>
                                    <br>
                                    @foreach ($data->banner_lelang_image as $item)
                                    <img class="ms-auto" src="{{ asset('storage/image/'.$item->gambar) }}"
                                        style="width:100px; height:60px; box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;">
                                    @endforeach
                                </div>
                                <div class="form-group">
                                    <label>Banner Web <small>(disarankan : upload 3 file, width:1000px height:900px) </small><span
                                            style="color: red">*</span></label>
                                    <input type="file" class="form-control" name="gambar[]" id="gambar" required multiple>
                                </div>
                                <div id="preview" class="review"></div>
                                <div class="d-flex justify-content-end">
                                    <button class="me-auto btn btn-success mt-3" type="submit">Simpan</button>
                                </div>
                            </form>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
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
            if (!/\.(jpe?g|png|jpg)$/i.test(file.name)) {
                alert("Hanya file gambar dengan ekstensi .jpeg, .jpg, .png, yang diperbolehkan.");
                document.querySelector('#gambar').value = '';
                return;
            }
            if (file.size > 400 * 1024) {
                alert("Ukuran file melebihi batas maksimum 400 KB.");
                document.querySelector('#gambar').value = '';
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

</script>
@endsection
