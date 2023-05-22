@extends('app.layouts')
@section('content')
<style>
    .review img{
        margin-bottom: 20px;
        margin-left: 20px;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
    }
</style>
<div class="section-header">
    <h1>Data Lelang</h1>
</div>
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Daftar Barang Lelang</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success text-center alert-dismissible fade show" role="alert">
                <strong>{{ session('success') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#exampleModal">
                <span class="text">+ Tambah</span>
            </button>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Barang Lelang</th>
                        <th scope="col">Nama Pemilik</th>
                        <th scope="col">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $no = 1;
                    @endphp
                    @forelse ($data as $index => $d)
                    <tr>
                        <td>{{$index + $data->firstItem()}}</td>
                        <td>{{$d->barang}}</td>
                        <td>{{$d->nama_pemilik}}</td>
                        <td>
                            <div class="dropdown d-inline">
                                <i class="fas fa-ellipsis-v cursor-pointer" style="cursor:pointer" id="dropdownMenuButton2"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                <form action="{{route('delete-barang-lelang', $d->id)}}" method="POST"
                                    onsubmit="return confirm('Apakah anda yakin akan menghapus data ini ?');">
                                    <div class="dropdown-menu" x-placement="bottom-start"
                                        style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                                        <a class="dropdown-item has-icon"
                                            href="{{route('detail-barang-lelang',$d->id)}}"><i
                                                class="fas fa-info-circle"></i>Detail</a>
                                        <a class="dropdown-item has-icon"
                                            href="{{ route('edit-barang-lelang', $d->id) }}"><i
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
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Input Barang Lelang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('add-barang-lelang')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Kategori Barang Lelang</label>
                        <select class="form-control" name="kategoribarang_id" required>
                            @foreach ($kategori as $item)
                            <option value="{{ $item->id }}">{{ $item->kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" class="form-control" name="barang" required >
                    </div>
                    <div class="form-group">
                        <label>Nama Pemilik Barang Lelang</label>
                        <input type="text" class="form-control" name="nama_pemilik" required >
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea class="form-control" name="keterangan"  required></textarea>
                    </div>
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

    function formatNumber(input) {
      // Menghilangkan karakter selain angka
      var num = input.value.replace(/[^0-9]/g, '');
      
      // Memformat angka menjadi format ribuan dan desimal
      var formattedNum = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);
    
      // Memasukkan nilai format ke dalam input
      input.value = formattedNum;
    }

</script>