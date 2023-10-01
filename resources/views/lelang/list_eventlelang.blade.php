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
                     data: "judul",
                 },
                 {
                     data: "waktu",
                 },
                 {
                     data: "link_lokasi",
                 },
                 
                 {
                    data: null,
                    render: function (data, type, row, meta) {
                        // console.log(data.lot_item);
                        // var encryptedId = "{{ Crypt::encrypt("+ data.id +") }}"; // Mengenkripsi id menggunakan Crypt::encrypt()
                        // console.log(encryptedId);
                        var deleteUrl = '/delete-event-lelang/' + data.id;
                        var editUrl = '/edit-event-lelang/' + data.id;
                        var bidding = '';
                        if (data && data.lot_item && data.lot_item[0] && data.lot_item[0].id) {
                            bidding = '/bidding-event-lelang/' + data.id + '?lot=' + data.lot_item[0].id;
                        }
                        var eventDate = new Date(row.waktu);
                        var today = new Date();
                        var convert_eventDate = eventDate.getFullYear()+'-'+(eventDate.getMonth()+1)+'-'+eventDate.getDate(); 
                        var convert_today = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate(); 
                        // console.log(woi);
                        return ` <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan menghapus data ini ?');">
                                <span><a class="btn btn-primary" href="${editUrl}"><i class="far fa-edit"></i></a></span>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="PUT">
                                <button class="btn btn-danger" type="submit"><i class="far fa-trash-alt"></i></button>
                                ${convert_eventDate == convert_today ? `<span><a class="btn btn-success" target="_blank" href="${bidding}"><i class="fas fa-hand-paper"></i></a></span>` : ''}
                            </form>`
                    }
                 },
             ],
         });
         $('#event-notactive').DataTable({
             processing: true,
             ordering: false,
             responsive: true,
             ajax: {
                 url: '{{ url()->current() }}',
                 data: function (data) {
                     data.status_data = 'not-active';
                 }
             },
             columns: [{
                     render: function (data, type, row, meta) {
                         return meta.row + meta.settings._iDisplayStart + 1;
                     },
                 },
                 {
                     data: "judul",
                 },
                 {
                     data: "waktu",
                 },
                 {
                     data: "link_lokasi",
                 },
                 {
                     data: null,
                     render: function (data) {
                         var activeurl = '/active-event-lelang/' + data.id;
                         return `
                         <form action="${activeurl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan mengaktifkan data ini ?');">
                         <input type="hidden" name="_token" value="{{ csrf_token() }}">
                         <input type="hidden" name="_method" value="PUT">
                         <button class="btn btn-success" type="submit"><i class="fas fa-sync-alt"></i></button>
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
                <h5 class="modal-title" id="eventlabel">Form Input Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('add-event-lelang')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Event <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="judul" placeholder="Judul event" required>
                    </div>
                    <div class="form-group">
                        <label>Jenis Kategori Lelang <span style="color: red">*</span></label>
                        <select class="form-control selectric" name="kategori_id">
                            @foreach ($data as $item)
                            <option value="{{ $item->id }}">{{ $item->kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Waktu Event <span style="color: red">*</span></label>
                        <input type="datetime-local" class="form-control" name="waktu" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat Event <span style="color: red">*</span></label>
                        <textarea class="form-control" name="alamat" required></textarea>
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
@endsection
