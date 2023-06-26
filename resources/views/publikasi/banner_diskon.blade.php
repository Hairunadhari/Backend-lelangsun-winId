@extends('app.layouts')
@section('content')

<div class="section-header">
    <h1>Data Publikasi</h1>
</div>
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Daftar Banner Diskon</h4>
        </div>
        <form action="" method="post">
            
        </form>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible text-center fade show" role="alert">
                <strong>{{ session('success') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            <div class="row">
                <div class="col-7">
                    <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#exampleModal">
                        <span class="text">+ Tambah</span>
                    </button>
                </div>
                <div class="col-5">
                    {{-- <form>
                        <div class="input-group">
                          <input type="text" id="cari" name="cari" class="form-control" placeholder="Search...">
                          <div class="input-group-btn">
                          </div>
                        </div>
                      </form> --}}
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Gambar</th>
                            <th scope="col">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="alldata">
                        @forelse ($data as $index => $d)
                        <tr>
                            <td>{{$index + $data->firstItem()}}</td>
                            <td>
                                <img src="{{ asset('/storage/image/'.$d->gambar) }}" class="rounded m-2" style="width: 100px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; ">
                            </td>
                            <td>
                                    <form action="{{route('delete-banner-diskon', $d->id)}}" method="POST"
                                        onsubmit="return confirm('Apakah anda yakin akan menghapus data ini ?');">
                                            <span><a class="btn btn-primary" href="{{ route('edit-banner-diskon', $d->id) }}"><i
                                                    class="far fa-edit"></i>Edit</a></span>
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger " type="submit"><i
                                                    class="far fa-trash-alt"></i> Hapus</button>
                                    </form>
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
</div>
@endsection
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Input Banner</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('add-banner-diskon')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Gambar</label>
                        <input type="file" class="form-control" name="gambar" required id="gambar">
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
<script>
    function previewImages() {
        var preview = document.querySelector('#preview');
        if (this.files) {
            [].forEach.call(this.files, readAndPreview);
        }
        function readAndPreview(file) {
            if (!/\.(jpe?g|png|gif)$/i.test(file.name)) {
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
