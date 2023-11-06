@extends('app.layouts')
@section('content')
<style>
    #preview img,
    .form-group img {
        margin-bottom: 20px;
        margin-left: 20px;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
    }

</style>
<div class="section-body">
    <form action="{{route('superadmin.update-peserta-npl', $data->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h4>Edit Peserta</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama <span style="color: red">*</span></label>
                    <input type="text" class="form-control" name="nama" value="{{$data->nama}}" required>
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label>Email <span style="color: red">*</span></label>
                        <input type="email" class="form-control" name="email" value="{{$data->email}}" required>
                    </div>
                    <div class="form-group col-6">
                        <label>Telepon <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="no_hp" value="{{$data->no_hp}}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Alamat <span style="color: red">*</span></label>
                    <textarea class="form-control" name="alamat" required>{{$data->alamat}}</textarea>
                </div>
                <div class="row mb-3">
                    <div class="form-group col-6">
                        <label>NIK <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="nik" value="{{$data->nik}}" required>
                    </div>
                    <div class="form-group col-6">
                        <label>NPWP <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="npwp" value="{{$data->npwp}}" required>
                    </div>
                </div>
                    <div class="row">
                        <div class="col-6"><img src="{{ asset('storage/image/'.$data->foto_ktp) }}" width="150" height="150" style=" box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;"></div>
                        <div class="col-6"><img src="{{ asset('storage/image/'.$data->foto_npwp) }}" width="150" height="150" style=" box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;"></div>
                    </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label>Foto KTP</label>
                        <input type="file" class="form-control" name="foto_ktp" value="{{$data->foto_ktp}}"
                            id="gambarktp">
                        <div id="previewktp" class="mt-3"></div>
                    </div>
                    <div class="form-group col-6">
                        <label>Foto NPWP</label>
                        <input type="file" class="form-control" name="foto_npwp" value="{{$data->foto_npwp}}"
                            id="gambarnpwp">
                        <div id="previewnpwp" class="mt-3"></div>
                    </div>
                </div>
                 <div class="card-footer text-right">
                <button class="btn btn-primary mr-1" type="submit">Submit</button>
            </div>
            </div>
        </div>
    </form>
</div>
<script>

    function previewgambarktp() {
        var preview = document.querySelector('#previewktp');

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
    document.querySelector('#gambarktp').addEventListener("change", previewgambarktp);

    function previewgambarnpwp() {
        var preview = document.querySelector('#previewnpwp');

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
    document.querySelector('#gambarnpwp').addEventListener("change", previewgambarnpwp);

</script>
@endsection
