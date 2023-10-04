@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="w-100">Daftar Peserta</h4>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#pembeliannplmodal">
                        <span class="text">+ Tambah Peserta</span>
                    </button>
                    {{-- <button type="button" class="btn btn-success" data-toggle="modal" data-target="#npl">
                        <span class="text">+ Tambah NPL Peserta</span>
                    </button> --}}
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
                    </ul>
                    <div class="tab-content" id="myTabContent2">
                        <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="home-tab3">
                            <table class="table table-striped w-100" id="active">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Nominal</th>
                                        <th>Status Pembayaran</th>
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
<script type="text/javascript">
    $(document).ready(function () {
        $('#active').DataTable({
            processing: true,
            ordering: false,
            searching: true,
            serverSide: true,
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
                    data: "nama_pemilik",
                    render: function (data, type, row, meta) {
                        if (data == null) {
                            return `<span>Bidder Offline</span>`
                        } else {
                            return `<span>`+data+`</span>`
                        }
                    },
                },
                {
                    data: "nominal",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return parseInt(data).toLocaleString('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                            });
                        }
                        return data;
                    }
                },
                {
                    data: "status_pembayaran",
                    render: function (data, type, row, meta) {
                        if (data == 'Belum Bayar') {
                            return `<span class="badge bg-primary text-white">Belum Bayar</span>`
                        } else {
                            return `<span class="badge bg-success text-white">Lunas</span>`
                        }
                    },
                },
            ],
        });

    });

</script>
@endsection

