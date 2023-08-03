@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="w-100">Daftar Event</h4>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#pembeliannplmodal">
                        <span class="text">+ Tambah</span>
                    </button>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible text-center fade show" role="alert">
                        <strong>{{ session('success') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <ul class="nav nav-pills" id="myTab3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab"
                                aria-controls="home" aria-selected="true">Peserta Aktif</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab"
                                aria-controls="profile" aria-selected="false">Peserta Tidak Aktif</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="verif-tab3" data-toggle="tab" href="#verif3" role="tab"
                                aria-controls="verif" aria-selected="false">Verifikasi NPL</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent2">
                        <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="home-tab3">
                            <table class="table table-striped w-100" >
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Telpon</th>
                                        <th>Alamat</th>
                                        <th>NPL</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">
                            <table class="table table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Telpon</th>
                                        <th>Alamat</th>
                                        <th>NPL</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="verif3" role="tabpanel" aria-labelledby="verif-tab3">
                            <table class="table table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Telpon</th>
                                        <th>Alamat</th>
                                        <th>NPL</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<style>
    .form-group img {
        padding: 0rem;
        border:1px solid #dee2e6;
    }
</style>
<!-- Modal -->
<div class="modal fade" id="pembeliannplmodal" tabindex="-1" role="dialog" aria-labelledby="pembeliannpllabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pembeliannpllabel">Form Input Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                        <div class="form-group">
                            <label>Nama <span style="color: red">*</span></label>
                            <input type="text" class="form-control" name="penyelenggara" required>
                        </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label>Email <span style="color: red">*</span></label>
                            <input type="text" class="form-control" name="penyelenggara" required>
                        </div>
                        <div class="form-group col-6">
                            <label>Telepon <span style="color: red">*</span></label>
                            <input type="text" class="form-control" name="penyelenggara" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Alamat <span style="color: red">*</span></label>
                        <textarea class="form-control" name="alamat_lokasi"></textarea>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label>NIK <span style="color: red">*</span></label>
                            <input type="text" class="form-control" name="tgl_mulai" required>
                        </div>
                        <div class="form-group col-6">
                            <label>NPWP <span style="color: red">*</span></label>
                            <input type="text" class="form-control" name="tgl_selesai" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label>Foto KTP</label>
                            <input type="file" class="form-control" name="gambar" required id="gambarktp">
                        <div id="previewktp" class="mt-3"></div>
                        </div>
                        <div class="form-group col-6">
                            <label>Foto NPWP</label>
                            <input type="file" class="form-control" name="gambar" required id="gambarnpwp">
                        <div id="previewnpwp" class="mt-3"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
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

<!-- /.container-fluid -->
