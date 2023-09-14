@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="w-100">Daftar Lot</h4>
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
              <table class="table table-striped w-100" id="lot">
                <thead>                                 
                  <tr>
                    <th>No</th>
                    <th>Nama Event</th>
                    <th>Tanggal Event</th>
                    <th>Lokasi Event</th>
                    <th>Jumlah LOT</th>
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
  <script>
    $(document).ready(function () {
         $('#lot').DataTable({
             processing: true,
             ordering: false,
             responsive: true,
             ajax: {
                 url: '{{ url()->current() }}',
             },
             columns: [{
                     render: function (data, type, row, meta) {
                         return meta.row + meta.settings._iDisplayStart + 1;
                     },
                 },
                 {
                     data: "event_lelang.judul",
                 },
                 {
                     data: "tanggal",
                 },
                 {
                     data: "event_lelang.link_lokasi",
                 },
                 {
                     data: "lot_item",
                        render: function (data, type, row, meta) {
                        return data.length; // Menampilkan jumlah total "lot" dalam kolom "lot"
                    },
                 },
                 
                 {
                     data: null,
                     render: function (data,type,row,meta) {
                         var addUrl = '/form-add-lot/' + data.id;
                         var editUrl = '/form-edit-lot/' + data.id;
                             return `
                            <span><a class="btn btn-primary" href="${editUrl}"><i class="far fa-edit"></i></a></span>
                            `;
                     },
                 },
             ],
         });
     });
  </script>
@endsection