@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>List Review Produk</h4>
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
                                aria-controls="home" aria-selected="true">Aktif</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab"
                                aria-controls="profile" aria-selected="false">Tidak Aktif</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent2">
                        <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="home-tab3">
                            <table class="table table-striped w-100" id="review">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Customer</th>
                                        <th>Rating</th>
                                        <th>Status</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">
                            <table class="table table-striped w-100" id="review-notactive"
                                style="width:100% !important;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Customer</th>
                                        <th>Rating</th>
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
    $(document).ready(function () {
        $('#review').DataTable({
            processing: true,
            ordering: false,
            ajax: {
                url: '{{ url()->current() }}',
                data: function (data) {
                    data.status = 'active';
                }
            },
            columns: [{
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "user.name",
                },
                {
                    data: "rating",
                },
                {
                    data: "status",
                    render: function (data, type, row, meta) {
                        if (data == "active") {
                            badge = `<span class="badge badge-success">ACTIVE</span>`
                        } else if (data == "not-active") {
                            badge = `<span class="badge badge-danger">NOT-ACTIVE</span>`
                        }
                        return badge;
                    }
                },
                {
                    data: null,
                    render: function (data) {
                        var updateUrl = '/nonactive-review/' + data.id;
                        var editUrl = '/detail-review/' + data.id;
                        return `
                        <form action="${updateUrl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan menonaktifkan data ini ?');">
                        <span><a class="btn btn-primary" href="${editUrl}"><i class="far fa-edit"></i>Detail/Reply</a></span>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">
                        <button class="btn btn-danger" type="submit"><i class="far fa-trash-alt"></i>NonActive</button>
                        </form>
                    `;
                    },
                },
            ],
        });
    });

    $(document).ready(function () {
        $('#review-notactive').DataTable({
            processing: true,
            ordering: false,
            ajax: {
                url: '{{ url()->current() }}',
                data: function (data) {
                    data.status = 'not-active';
                }
            },
            columns: [{
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "user.name",
                },
                {
                    data: "rating",
                },
                {
                    data: "status",
                    render: function (data, type, row, meta) {
                        if (data == "not-active") {
                            badge = `<span class="badge badge-danger">NOT-ACTIVE</span>`
                        }
                        return badge;
                    }
                },
                {
                    data: null,
                    render: function (data) {
                        var updateUrl = '/active-review/' + data.id;
                        var editUrl = '/detail-review/' + data.id;
                        return `
                        <form action="${updateUrl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan mengaktifkan data ini ?');">
                        <span><a class="btn btn-primary" href="${editUrl}"><i class="far fa-edit"></i>Detail/Reply</a></span>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">
                        <button class="btn btn-success" type="submit">Active</button>
                        </form>
                    `;
                    },
                },
            ],
        });
    });

</script>
@endsection
