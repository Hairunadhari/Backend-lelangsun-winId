@extends('app.layouts')
@section('content')
<style>
    .form-group img {
        padding: 0rem;
        border: 1px solid #dee2e6;
    }
  
</style>
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Form Tambah Peserta</h4>
        </div>
        <div class="card-body">
            <form action="{{route('superadmin.add-peserta-npl')}}" method="post" enctype="multipart/form-data">
                @csrf
                    <div class="form-group">
                        <label>Nama <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="nama" required>
                        @if ($errors->has('nama'))
                        <div class="warn text-danger"><small class="text-alert">eror!</small></div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label>Email <span style="color: red">*</span></label>
                            <input type="email" class="form-control" name="email" required>
                            @if ($errors->has('email'))
                            <div class="warn text-danger"><small class="text-alert">eror!</small></div>
                            @endif
                        </div>
                        <div class="form-group col-6">
                            <label>Telepon <span style="color: red">*</span></label>
                            <input type="number" class="form-control" name="no_telp" required>
                            @if ($errors->has('no_telp'))
                            <div class="warn text-danger"><small class="text-alert">eror!</small></div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Alamat <span style="color: red">*</span></label>
                        <textarea class="form-control" name="alamat" required></textarea>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label>NIK</label>
                            <input type="text" class="form-control" name="nik">
                        </div>
                        <div class="form-group col-6">
                            <label>NPWP</label>
                            <input type="text" class="form-control" name="npwp">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label>Foto KTP</label>
                            <input type="file" class="form-control" accept=".jpg,.png,.jpeg" name="foto_ktp" id="gambarktp">
                            <div id="previewktp" class="mt-3"></div>
                        </div>
                        <div class="form-group col-6">
                            <label>Foto NPWP</label>
                            <input type="file" class="form-control" accept=".jpg,.png,.jpeg" name="foto_npwp" id="gambarnpwp">
                            <div id="previewnpwp" class="mt-3"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label class="form-label">Password <span style="color: red">*</span></label>
                            <input type="password" class="form-control" name="password" required>
                            @if ($errors->has('password'))
                            <div class="warn text-danger"><small class="text-alert">Password harus memiliki 5 karakter!</small></div>
                            @endif
                        </div>
                        <div class="form-group col-6">
                            <label class="form-label">Confirm Password <span style="color: red">*</span></label>
                            <input type="password" class="form-control" name="confirm_password" required>
                            @if ($errors->has('confirm_password'))
                            <div class="warn text-danger"><small class="text-alert">Konfirmasi Password Tidak Cocok!</small></div>
                            @endif
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
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
            if (!/\.(jpe?g|png)$/i.test(file.name)) {
                alert(file.name + " format tidak sesuai");
                document.querySelector('#gambarktp').value = '';
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
            if (!/\.(jpe?g|png)$/i.test(file.name)) {
                alert(file.name + " format tidak sesuai");
                document.querySelector('#gambarnpwp').value = '';
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
    document.querySelector('#gambarnpwp').addEventListener("change", previewgambarnpwp);

</script>
@endsection
