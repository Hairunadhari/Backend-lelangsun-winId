@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="w-100">Daftar Event</h4>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#eventmodal">
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
                                aria-controls="home" aria-selected="true">Event Aktif</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab"
                                aria-controls="profile" aria-selected="false">Event Tidak Aktif</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent2">
                        <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="home-tab3">
                            <table class="table table-striped w-100" id="event">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Event</th>
                                        <th>Tanggal Event</th>
                                        <th>Lokasi</th>
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
                                        <th>Gambar</th>
                                        <th>Judul</th>
                                        <th>Jenis</th>
                                        <th>Tiket</th>
                                        <th>Link Meet</th>
                                        <th>Status</th>
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
    // $(document).ready(function () {
    //     $('#event').DataTable({
    //         processing: true,
    //         ordering: false,
    //         responsive: true,
    //         ajax: {
    //             url: '{{ url()->current() }}',
    //             data: function (data) {
    //                 data.status_data = 'active';
    //             }
    //         },
    //         columns: [{
    //                 render: function (data, type, row, meta) {
    //                     return meta.row + meta.settings._iDisplayStart + 1;
    //                 },
    //             },
    //             {
    //                 data: "gambar",
    //                 render: function (data) {
    //                     return '<img src="/storage/image/' + data +
    //                         '"style="width: 150px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin:5px; padding:0.25rem; border:1px solid #dee2e6; ">';
    //                 },
    //             },
    //             {
    //                 data: "judul",
    //             },
    //             {
    //                 data: "jenis",
    //             },
    //             {
    //                 data: "tiket",
    //             },
    //             {
    //                 data: null,
    //                 render: function (data) {
    //                     return `<a href="${data.link}">${data.link}</a>`
    //                 }
    //             },
    //             {
    //                 data: "status",
    //                 render: function (data, type, row, meta) {
    //                     if (data == "akan datang") {
    //                         badge = `<span class="badge badge-primary">Coming Soon</span>`
    //                     } else if (data == "sedang berlangsung") {
    //                         badge =
    //                             `<span class="badge badge-success">Sedang Berlangsung</span>`
    //                     } else if (data == "selesai") {
    //                         badge = `<span class="badge badge-light">Selesai</span>`
    //                     }
    //                     return badge;
    //                 }
    //             },
    //             {
    //                 data: null,
    //                 render: function (data) {
    //                     var deleteUrl = '/delete-event/' + data.id;
    //                     var editUrl = '/edit-event/' + data.id;
    //                     return `
    //                 <div class="dropdown d-inline">
    //                     <i class="fas fa-ellipsis-v cursor-pointer" style="cursor:pointer" id="dropdownMenuButton2"
    //                         data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
    //                     <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan menghapus data ini ?');">
    //                         <div class="dropdown-menu" x-placement="bottom-start"
    //                             style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
    //                             <a class="dropdown-item has-icon" href="${editUrl}"><i class="far fa-edit"></i>Edit</a>
    //                             <input type="hidden" name="_token" value="{{ csrf_token() }}">
    //                             <input type="hidden" name="_method" value="PUT">
    //                             <button class="btn btn-danger" style="margin-left: 20px;" type="submit"><i class="far fa-trash-alt"></i> Hapus</button>
    //                         </div>
    //                     </form>
    //                 </div>
    //                 `;
    //                 },
    //             },
    //         ],
    //     });
    // });

    // $(document).ready(function () {
    //     $('#event-notactive').DataTable({
    //         processing: true,
    //         ordering: false,
    //         responsive: true,
    //         ajax: {
    //             url: '{{ url()->current() }}',
    //             data: function (data) {
    //                 data.status_data = 'not-active';
    //             }
    //         },
    //         columns: [{
    //                 render: function (data, type, row, meta) {
    //                     return meta.row + meta.settings._iDisplayStart + 1;
    //                 },
    //             },
    //             {
    //                 data: "gambar",
    //                 render: function (data) {
    //                     return '<img src="/storage/image/' + data +
    //                         '"style="width: 150px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin:5px; padding:0.25rem; border:1px solid #dee2e6; ">';
    //                 },
    //             },
    //             {
    //                 data: "judul",
    //             },
    //             {
    //                 data: "jenis",
    //             },
    //             {
    //                 data: "tiket",
    //             },
    //             {
    //                 data: null,
    //                 render: function (data) {
    //                     return `<a href="${data.link}">${data.link}</a>`
    //                 }
    //             },
    //             {
    //                 data: "status",
    //                 render: function (data, type, row, meta) {
    //                     if (data == "akan datang") {
    //                         badge = `<span class="badge badge-primary">Coming Soon</span>`
    //                     } else if (data == "sedang berlangsung") {
    //                         badge =
    //                             `<span class="badge badge-success">Sedang Berlangsung</span>`
    //                     } else if (data == "selesai") {
    //                         badge = `<span class="badge badge-light">Selesai</span>`
    //                     }
    //                     return badge;
    //                 }
    //             },
    //             {
    //                 data: null,
    //                 render: function (data) {
    //                     var activeurl = '/active-event/' + data.id;
    //                     return `
    //                     <form action="${activeurl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan mengaktifkan data ini ?');">
    //                     <input type="hidden" name="_token" value="{{ csrf_token() }}">
    //                     <input type="hidden" name="_method" value="PUT">
    //                     <button class="btn btn-success" type="submit"><i class="fas fa-sync-alt"></i></button>
    //                     </form>
    //                 `;
    //                 },
    //             },
    //         ],
    //     });
    // });

</script>
@endsection
<!-- Modal -->
<div class="modal fade" id="eventmodal" tabindex="-1" role="dialog" aria-labelledby="eventlabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventlabel">Form Input Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('add-event')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Event <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="judul" placeholder="Judul event" required>
                    </div>
                    <div class="form-group">
                        <label>Waktu Event <span style="color: red">*</span></label>
                        <input type="date" class="form-control" name="judul" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat Event <span style="color: red">*</span></label>
                        <textarea class="form-control" name="alamat_lokasi"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Link Lokasi <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="link_lokasi" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Event<span style="color: red">*</span></label>
                        <textarea class="summernote-simple" placeholder="keterangan..." name="deskripsi"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
