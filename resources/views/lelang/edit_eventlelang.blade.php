@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Edit Event</h4>
            </div>
            <form action="{{route('superadmin.update-event-lelang', $data->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label>Image <small>(png, jpg, jpeg)</small> <span style="color: red">*</span></label>
                        <input type="file" class="form-control" name="gambar"  id="gambar">
                    <div id="preview" class="mt-3 mb-3"></div>
                    <div class="form-group">
                        <label>Judul Event</label>
                        <input type="text" class="form-control" value="{{ $data->judul }}" name="judul">
                    </div>
                    <div class="form-group">
                        <label>Jenis Kategori Lelang <span style="color: red">*</span></label>
                        <select class="form-control select2" name="kategori_id">
                             @foreach ($kategori as $item)
                            <option value="{{ $item->id }}"
                                {{ $item->id == $data->kategori_barang_id ? 'selected' : '' }}>
                                {{ $item->kategori }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Waktu Event <span style="color: red">*</span></label>
                        <input type="datetime-local" class="form-control" name="waktu" value="{{$data->waktu}}">
                    </div>
                    <div class="form-group">
                        <label>Alamat Event <span style="color: red">*</span></label>
                        <textarea class="form-control" name="alamat" >{{$data->alamat}}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Link Lokasi <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="link_lokasi" value="{{$data->link_lokasi}}">
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Event<span style="color: red">*</span></label>
                        <textarea class="summernote-simple" placeholder="keterangan..." name="deskripsi">{{$data->deskripsi}}</textarea>
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
</script>
@endsection
