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
                    <label>Nama</label>
                    <input type="text" class="form-control" name="name" value="{{$data->name}}">
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label>Email</label>
                        <input type="email" class="form-control"  value="{{$data->email}}" name="email">
                        {{-- <input type="email" class="form-control"  value="{{$data->email}}" readonly> --}}
                    </div>
                    <div class="form-group col-6">
                        <label>Telepon</label>
                        <input type="number" class="form-control" name="no_telp" value="{{$data->no_telp}}">
                        @if ($errors->has('no_telp'))
                        <div class="warn text-danger"><small class="text-alert">eror!</small></div>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea class="form-control" name="alamat">{{$data->alamat}}</textarea>
                </div>
                <div class="row mb-3">
                    <div class="form-group col-6">
                        <label>NIK</label>
                        <input type="text" class="form-control" name="nik" value="{{$data->nik}}">
                    </div>
                    <div class="form-group col-6 tes">
                        <label>NPWP</label>
                        <input type="text" class="form-control" name="npwp" value="{{$data->npwp}}">
                    </div>
                </div>
                    <div class="row">
                        <div class="col-6"><img src="{{ asset('storage/image/'.$data->foto_ktp) }}" width="150" height="150" style=" box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;"></div>
                        <div class="col-6"><img src="{{ asset('storage/image/'.$data->foto_npwp) }}" width="150" height="150" style=" box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;"></div>
                    </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label>Foto KTP</label>
                        <input type="file" class="form-control" name="foto_ktp" accept=".jpg, .png, .jpeg" value="{{$data->foto_ktp}}"
                            id="gambarktp">
                        <div id="previewktp" class="mt-3"></div>
                    </div>
                    <div class="form-group col-6">
                        <label>Foto NPWP</label>
                        <input type="file" class="form-control" name="foto_npwp"  accept=".jpg, .png, .jpeg" value="{{$data->foto_npwp}}"
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
