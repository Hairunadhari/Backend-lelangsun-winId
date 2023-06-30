@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="w-100">Daftar Toko</h4>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#tokomodal">
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
                    <table class="table table-striped" id="toko">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Toko</th>
                                <th>Logo</th>
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
        $('#toko').DataTable({
            processing: true,
            ordering: false,
            fixedColumns: true,
            // fixedHeader: true,
            ajax: '{{ url()->current() }}',
            columns: [{
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "toko",
                },
                {
                    data: "logo",
                    render: function (data) {
                        return '<img src="/storage/image/' + data +
                            '"style="width: 150px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin:5px; ">';
                    },
                },
                {
                data: null,
                render: function (data) {
                    var deleteUrl = '/deletetoko/' + data.id;
                    var editUrl = '/edittoko/' + data.id;
                    var detailUrl = '/detailtoko/' + data.id;
                    return `
                    <div class="dropdown d-inline">
                        <i class="fas fa-ellipsis-v cursor-pointer" style="cursor:pointer" id="dropdownMenuButton2"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                        <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan menghapus data ini ?');">
                            <div class="dropdown-menu" x-placement="bottom-start"
                                style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item has-icon" href="${detailUrl}"><i class="fas fa-info-circle"></i>Detail</a>
                                <a class="dropdown-item has-icon" href="${editUrl}"><i class="far fa-edit"></i>Edit</a>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button class="btn btn-danger" style="margin-left: 20px;" type="submit"><i class="far fa-trash-alt"></i> Hapus</button>
                            </div>
                        </form>
                    </div>
                    `;
                },
            },
            ],
        });
    });

    function previewImages() {
        var preview = document.querySelector('#preview');
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
    document.querySelector('#gambar').addEventListener("change", previewImages);

    document.querySelector('#resetButton').addEventListener('click', function () {
        document.querySelector('#preview').innerHTML = '';
    });

</script>
@endsection
<!-- Modal -->
<div class="modal fade" id="tokomodal" tabindex="-1" role="dialog" aria-labelledby="tokoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tokoLabel">Form Input Toko</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('addtoko')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Toko</label>
                        <input type="text" class="form-control" name="toko" required id="exampleInputEmail1">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Logo</label>
                        <input type="file" class="form-control" name="logo" required id="gambar">
                    </div>
                    <div id="preview"></div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-warning" id="resetButton">Reset</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- /.container-fluid -->
