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
                <div class="row">
                    <div class="form-group col-6">
                        <label>Penyelenggara <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="penyelenggara" value="{{$data->penyelenggara}}" >
                    </div>
                    <div class="form-group col-6">
                        <label>Judul <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="judul" value="{{$data->judul}}">
                    </div>
                </div>
                <div class="form-group">
                    <label>Deskripsi <span style="color: red">*</span></label>
                    <textarea class="summernote-simple" placeholder="keterangan..."
                        name="deskripsi">{{$data->deskripsi}}</textarea>
                </div>
                <div class="form-group">
                    <label>Alamat Lokasi <span style="color: red">*</span></label>
                    <textarea class="form-control" name="alamat_lokasi">{{$data->alamat_lokasi}}</textarea>
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label>Jenis <span style="color: red">*</span></label>
                        <select class="form-control selectric" name="jenis" >
                            <option value="Offline" <?= ($data->jenis == 'Offline' ? 'selected' : '')?>>Offline</option>
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label>Tiket <span style="color: red">*</span></label>
                        <select class="form-control selectric" name="tiket" <?php echo ($data->tiket === 'ada') ? 'checked' : ''; ?>>
                            <option value="Berbayar" <?= ($data->tiket == 'Berbayar' ? 'selected' : '')?>>Berbayar</option>
                            <option value="Gratis" <?= ($data->tiket == 'Gratis' ? 'selected' : '')?>>Gratis</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label>Tanggal Mulai <span style="color: red">*</span></label>
                        <input type="date" class="form-control" name="tgl_mulai" value="{{$data->tgl_mulai}}">
                    </div>
                    <div class="form-group col-6">
                        <label>Tanggal Selesai <span style="color: red">*</span></label>
                        <input type="date" class="form-control" name="tgl_selesai" value="{{$data->tgl_selesai}}">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label>Link Registrasi <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="link" value="{{$data->link}}">
                    </div>
                    <div class="form-group col-6">
                        <label>Link Lokasi <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="link_lokasi" value="{{$data->link_lokasi}}">
                    </div>
                </div>
                <div class="form-group">
                    <label>Image <span style="color: red">*</span></label>
                    <input type="file" class="form-control" name="gambar"  id="gambar">
                <div id="preview" class="mt-3"></div>
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
