@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="w-100">List Member Event</h4>
                     <form action="{{route('delete-all-member-event', $id)}}" method="POST" onsubmit="return confirm('Apakah anda yakin akan menghapus semua data ini ? data yg sudah terhapus tidak dapat dikembalikan');">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button class="btn btn-danger" style="margin-left: 20px;" type="submit"><i class="far fa-trash-alt"></i> Hapus Semua Member</button>
                    </form>
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
                            <table class="table table-striped w-100" id="event">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>No Telp</th>
                                        <th>Bukti Pembayaran</th>
                                        <th>Status</th>
                                        <th>Verifikasi</th>
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
        $('#event').DataTable({
            processing: true,
            ordering: false,
            responsive: true,
            ajax: '{{ url()->current() }}',
            columns: [{
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "user.name",
                },
                {
                    data: "user.email",
                },
                {
                    data: "user.no_telp",
                    render: function (data){
                        if(data == null){
                            return `<span>-</span>`
                        }else{
                            return data;
                        }
                    }
                },
                {
                    data: "bukti_bayar",
                    render: function (data) {
                        return '<a href="/storage/image/' + data + '" target="_blank"><img src="/storage/image/' + data + '" style="width: 100px; height: 100px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin: 5px; padding: 0.25rem; border: 1px solid #dee2e6;"></a>';
                    }
                },
                {
                    data: "status_verif",
                    render: function (data){
                        if(data == 1){
                            return `<span class="badge badge-success">Sudah Terverifikasi</span>`
                        }else{
                            return `<span class="badge badge-primary">Belum Terverifikasi</span>`
                        }
                    }
                },
                {
                    data: null,
                    render: function (data){
                        var verif = '/send-email-member/' + data.id;
                        return `<form action="${verif}" method="POST" onsubmit="return confirm('Apakah anda yakin akan memverifikasi data ini ? jika ya member akan dikirimkan info tentang event ini');">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="POST">
                                <button class="btn btn-primary" style="margin-left: 20px;"><i class="fas fa-paper-plane fa-lg"></i></button>
                        </form>`;
                    }
                },
                {
                    data:null,
                    render: function (data){
                        var delet = '/delete-member-event/' + data.id;
                        return `<form action="${delet}" method="POST" onsubmit="return confirm('Apakah anda yakin akan menghapus permanen data ini ?');">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button class="btn btn-danger" style="margin-left: 20px;" type="submit"><i class="far fa-trash-alt"></i></button>
                        </form>`;
                    }
                }
            ],
        });
    });
</script>
@endsection
