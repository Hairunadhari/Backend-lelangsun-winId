@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="w-100">Daftar Produk</h4>
                    <a href="{{route('form-input-produk')}}" class="btn btn-success">+ Tambah</a>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills" id="myTab3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab"
                                aria-controls="home" aria-selected="true">Produk Aktif</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab"
                                aria-controls="profile" aria-selected="false">Produk Tidak Aktif</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent2">
                        <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="home-tab3">
                            <table class="table table-striped w-100" id="t-produk-active">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Produk</th>
                                        <th scope="col">Cover Produk</th>
                                        <th scope="col">Tipe Barang</th>
                                        <th scope="col">Harga</th>
                                        <th scope="col">Stok</th>
                                        <th scope="col">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">
                            <table class="table table-striped w-100" id="t-produk-notactive">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Produk</th>
                                        <th scope="col">Cover Produk</th>
                                        <th scope="col">Tipe Barang</th>
                                        <th scope="col">Harga</th>
                                        <th scope="col">Stok</th>
                                        <th scope="col">Opsi</th>
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
            $('#t-produk-active').DataTable({
                // responsive: true,
                processing: true,
                ordering: false,
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
                        data: "nama",
                    },
                    {
                        data: "thumbnail",
                        render: function (data) {
                            return '<img src="/storage/image/' + data +
                                '"style="width: 100px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin:5px; padding:0.25rem; border:1px solid #dee2e6;">';
                        },
                    },
                    {
                        data: "tipe_barang",
                    },

                    {
                        data: "harga",
                        render: function(data, type, row, meta) {
                            if (type === 'display') {
                                // Mengubah data menjadi format mata uang dengan simbol IDR
                                return "Rp " + parseInt(data).toLocaleString('id-ID', {
                                    minimumFractionDigits: 0
                                });
                            }
                            return data;
                        }
                    },
                    {
                        data: "stok",
                    },
                    {
                        data: null,
                        render: function (data) {
                            var deleteUrl = '/deleteproduk/' + data.id;
                            var editUrl = '/editproduk/' + data.id;
                            var detailUrl = '/detailproduk/' + data.id;
                            return `
                        <div class="dropdown d-inline">
                            <i class="fas fa-ellipsis-v cursor-pointer" style="cursor:pointer" id="dropdownMenuButton2"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                        <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan menghapus data ini?');">
                                <div class="dropdown-menu" x-placement="bottom-start"
                                    style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    <a class="dropdown-item has-icon" href="${editUrl}"><i class="far fa-edit"></i>Edit</a>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="PUT">
                                    <button class="btn btn-danger" style="margin-left: 20px;" type="submit"><i class="far fa-trash-alt"></i>Hapus</button>
                                </div>
                            </form>
                        </div>
                        `;
                        },
                    },
                ],
            });
            
            $('#t-produk-notactive').DataTable({
                // responsive: true,
                processing: true,
                ordering: false,
                serverSide: true,
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
                        data: "nama",
                    },
                    {
                        data: "thumbnail",
                        render: function (data) {
                            return '<img src="/storage/image/' + data +
                                '"style="width: 100px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin:5px; padding:0.25rem; border:1px solid #dee2e6;">';
                        },
                    },
                    {
                        data: "tipe_barang",
                    },
                    {
                        data: "harga",
                        render: $.fn.dataTable.render.number(',', '.', 0, 'Rp. ')
                    },
                    {
                        data: "stok",
                    },
                    {
                        data: null,
                        render: function (data) {
                            var activeUrl = '/activeproduk/' + data.id;
                            return `
                            <form action="${activeUrl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan mengaktifkan data ini ?');">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="PUT">
                            <button class="btn btn-success" type="submit">Aktifkan</button>
                            </form>
                        `;
                        },
                    },
                ],
            });
    });
</script>
@endsection
