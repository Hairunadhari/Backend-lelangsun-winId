@extends('app.layouts')
@section('content')
<div class="section-header">
    <h1>Data E-commerce</h1>
</div>
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Daftar Kategori Produk</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible text-center fade show" role="alert">
                <strong>{{ session('success') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @elseif ($errors->has('kategori'))
            <div class="alert alert-danger alert-dismissible text-center fade show" role="alert">
                <strong>Kategori sudah terdaftar!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#exampleModal2">
                <span class="text">+ Tambah</span>
            </button>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Gambar</th>
                        <th scope="col">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $index => $d)
                    <tr>
                        <td>{{$index + $data->firstItem() }}</td>
                        <td>{{strtoupper($d->kategori)}}</td>
                        <td>
                            <img src="{{ asset('/storage/image/'.$d->gambar) }}" class="rounded m-2" style="width: 100px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; ">
                        </td>
                        <td>
                            <div class="dropdown d-inline">
                                <i class="fas fa-ellipsis-v cursor-pointer" style="cursor:pointer" id="dropdownMenuButton2"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                <form action="{{route('delete-kategori-produk', $d->id)}}" method="POST"
                                    onsubmit="return confirm('Apakah anda yakin akan menghapus data ini ?');">
                                    <div class="dropdown-menu" x-placement="bottom-start"
                                        style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                                        <a class="dropdown-item has-icon"
                                            href="{{route('detail-kategori-produk',$d->id)}}"><i
                                                class="fas fa-info-circle"></i>Detail</a>
                                        <a class="dropdown-item has-icon"
                                            href="{{ route('edit-kategori-produk', $d->id) }}"><i
                                                class="far fa-edit"></i>Edit</a>
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger " style="margin-left: 20px;" type="submit"><i
                                                class="far fa-trash-alt"></i> Hapus</button>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-left:20px">
            {{ $data->links() }}
        </div>
    </div>
</div>
@endsection
<!-- Modal -->
<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Input Kategori Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('add-kategori-produk')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kategori</label>
                        <input type="text" class="form-control" name="kategori" required>
                    </div>
                    <div class="form-group">
                        <label>Gambar</label>
                        <input type="file" class="form-control" name="gambar" id="gambar" required>
                    </div>
                    <div id="preview"></div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-warning" id="resetButton">Reset</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function previewImages() {
        var preview = document.querySelector('#preview');
        if (this.files) {
            [].forEach.call(this.files, readAndPreview);
        }
        function readAndPreview(file) {
            if (!/\.(jpe?g|png|gif|webp)$/i.test(file.name)) {
                return alert(file.name + " is not an image");
            }
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
    
    document.querySelector('#resetButton').addEventListener('click', function() {
        document.querySelector('#preview').innerHTML = '';
    });
</script>
<!-- /.container-fluid -->
