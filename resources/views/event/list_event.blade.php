@extends('app.layouts')
@section('content')
<style>
    .reviews img {
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
                    <h4 class="w-100">Daftar Service </h4>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#eventmodal">
                        <span class="text">+ Tambah</span>
                    </button>
                </div>
                <div class="card-body">
                    {{-- <ul class="nav nav-pills" id="myTab3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab"
                                aria-controls="home" aria-selected="true">Data Aktif</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab"
                                aria-controls="profile" aria-selected="false">Data Tidak Aktif</a>
                        </li>
                    </ul> --}}
                    <div class="tab-content" id="myTabContent2">
                        <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="home-tab3">
                            <table class="table table-striped w-100" id="event">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Poster</th>
                                        <th>Nama</th>
                                        <th>Jenis</th>
                                        <th>Tiket</th>
                                        <th>Status</th>
                                        <th>List Member</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">
                            <table class="table table-striped w-100" id="event-notactive">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Poster</th>
                                        <th>Judul</th>
                                        <th>Jenis</th>
                                        <th>Tiket</th>
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
<script>
    $(document).ready(function () {
        $('#event').DataTable({
            processing: true,
            ordering: false,
            responsive: true,
            ajax: {
                url: '{{ url()->current() }}',
                data: function (data) {
                    data.status_data = 'active';
                }
            },
            columns: [{
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "detail_gambar_event",
                    render: function (data) {
                        a = '';
                        if (data.length > 0) { // Periksa apakah data tidak null dan memiliki elemen
                            a += '<img src="/storage/image/' + data[0].gambar + '" style="max-width: 15vw; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin:5px; padding:0.25rem; border:1px solid #dee2e6; ">';
                        }else{
                            a += '-'
                        }

                        return a;
                    },
                },
                {
                    data: "judul",
                },
                {
                    data: "jenis",
                },
                {
                    data: "tiket",
                },
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        const today = new Date(); // Mendapatkan tanggal hari ini
                        const tanggalMulai = new Date(row
                            .tgl_mulai); // Mendapatkan tanggal_mulai dari baris data
                        const tanggalSelesai = new Date(row
                            .tgl_selesai); // Mendapatkan tanggal_mulai dari baris data
                        // console.log(tanggalSelesai);

                        if (tanggalSelesai < today) {
                            badge = `<span class="badge badge-light">Selesai</span>`;
                        } else if (tanggalMulai <= today) {
                            badge =
                                `<span class="badge badge-success">Sedang Berlangsung</span>`;
                        } else {
                            badge = `<span class="badge badge-primary">Coming Soon</span>`;
                        }
                        return badge;
                    }
                },
                {
                    data: null,
                    render: function (data) {
                        var list = '/superadmin/list-member-event/' + data.id;
                        return `<a class="btn btn-warning" href="${list}"><i class="fas fa-users"></i></a>`
                    }
                },
                {
                    data: null,
                    render: function (data) {
                        var deleteUrl = '/superadmin/delete-event/' + data.id;
                        var editUrl = '/superadmin/edit-event/' + data.id;
                        return `
                        <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan menghapus data ini ?');">
                                <span><a class="btn btn-primary btn-sm" href="${editUrl}"><i class="far fa-edit"></i>Edit</a></span>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="PUT">
                                <button class="btn btn-danger btn-sm" type="submit"><i class="far fa-trash-alt"></i> Hapus</button>
                        </form>
                    `;
                    },
                },
            ],
        });
    });

</script>
@endsection
@section('modal')
<!-- Modal -->
<div class="modal fade" id="eventmodal" tabindex="-1" role="dialog" aria-labelledby="eventlabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventlabel">Form Input Service</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('superadmin.add-event')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-6">
                            <label>Penyelenggara <span style="color: red">*</span></label>
                            <input type="text" class="form-control" name="penyelenggara" required>
                        </div>
                        <div class="form-group col-6">
                            <label>Nama Service<span style="color: red">*</span></label>
                            <input type="text" class="form-control" name="judul" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi <span style="color: red">*</span></label>
                        <textarea class="summernote-simple" placeholder="keterangan..." name="deskripsi"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Alamat Lokasi <span style="color: red">*</span></label>
                        <textarea class="form-control" name="alamat_lokasi"></textarea>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label>Jenis <span style="color: red">*</span></label>
                            <select class="form-control selectric" name="jenis" required>
                                <option value="Offline">Offline</option>
                                <option value="Online">Online</option>
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label>Tiket <span style="color: red">*</span></label>
                            <select class="form-control selectric" name="tiket" id="tiket" required
                                onchange="toggleDiv(this.value)">
                                <option value="Gratis">Gratis</option>
                                <option value="Berbayar">Berbayar</option>
                            </select>
                        </div>
                    </div>
                    <div id="inpharga" style="display: none;">
                        <div class="form-group">
                            <label>Harga <span style="color: red">*</span></label>
                            <input type="text" class="form-control" id="hargaInput" name="harga" required
                                onkeyup="formatNumber(this)">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label>Tanggal Mulai <span style="color: red">*</span></label>
                            <input type="date" class="form-control" name="tgl_mulai" required>
                        </div>
                        <div class="form-group col-6">
                            <label>Tanggal Selesai <span style="color: red">*</span></label>
                            <input type="date" class="form-control" name="tgl_selesai" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label>Link Meeting Online <small>(isi - jika tidak ada link)</small><span
                                    style="color: red">*</span></label>
                            <input type="text" class="form-control" name="link" required>
                        </div>
                        <div class="form-group col-6">
                            <label>Link Lokasi Offline<small>(isi - jika tidak ada link)</small><span
                                    style="color: red">*</span></label>
                            <input type="text" class="form-control" name="link_lokasi" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Poster Service <small>(disarankan: width 900px, height 470px) </small><span
                                style="color: red">*</span></label>
                        <div class="input-images"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<script>
    $('.input-images').imageUploader({
        imagesInputName: 'poster',
        maxSize: 2 * 1024 * 1024,

    });
    window.onload = function () {
        const tiket = document.getElementById("tiket");
        const inpharga = document.getElementById("inpharga");
        const atr = inpharga.querySelectorAll("input[required]");

        if (tiket.value == "Gratis") {
            tiket.style.display = "none";
            atr.forEach(input => {
                input.removeAttribute("required");
            });
        }
    };



    function formatNumber(input) {
        // Menghilangkan karakter selain angka
        var num = input.value.replace(/[^0-9]/g, '');

        // Memformat angka menjadi format ribuan dan desimal
        var formattedNum = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(num);

        // Memasukkan nilai format ke dalam input
        input.value = formattedNum;
    };

    function toggleDiv(value) {
        const inpharga = document.getElementById("inpharga");
        const hargaInput = inpharga.querySelectorAll("input[required]");

        if (value === "Berbayar") {
            inpharga.style.display = "block";
            hargaInput.required = true;
        } else {
            inpharga.style.display = "none";
            hargaInput.forEach(input => {
                input.removeAttribute("required");
            });
        }
    };

</script>
@endsection
