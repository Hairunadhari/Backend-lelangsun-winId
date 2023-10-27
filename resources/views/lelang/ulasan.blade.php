@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="w-100">Daftar Ulasan Pemenang Lelang</h4>
                </div>
                <div class="card-body">
                <table class="table table-striped w-100" id="tablebanner-1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Bintang</th>
                            <th>Ulasan</th>
                            <th>Aksi</th>
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
<script type="text/javascript">
    $(document).ready(function () {
        $('#tablebanner-1').DataTable({
            processing: true,
            ordering: false,
            searching: false,
            serverSide: true,
            ajax: '{{ url()->current() }}',
            columns: [{
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "nama",
                   
                },
                {
                    data: "bintang",
                   
                },
                {
                    data: "ulasan",
                   
                },
                {
                    data: null,
                    render: function (data) {
                        var deleteUrl = '/superadmin/delete-ulasan/' + data.id;
                        return `
                <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan menghapus data ini ?');">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <input type="hidden" name="_method" value="DELETE">
                  <button class="btn btn-danger" type="submit"><i class="far fa-trash-alt"></i></button>
                </form>
              `;
                    },
                },
            ],
        });
    });

</script>
@endsection
