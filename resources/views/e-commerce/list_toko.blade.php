@extends('app.layouts')
@section('content')

<div class="section-header">
    <h1>Data E-commerce</h1>
</div>
<div class="section-body">
    <div class="card">
        <form action="{{url('api/add-order')}}" method="post">
        @csrf
        <input type="text" name="user_id" id="">
        <input type="text" name="produk_id" id="">
        <input type="text" name="qty" id="">
        <input type="text" name="pengiriman" id="">
        <input type="text" name="lokasi_pengiriman" id="">
        <input type="text" name="nama_penigirim" id="">
        <input type="text" name="metode_pembayaran" id="">
        <input type="text" name="total_pembayaran" id="">
        <button type="submit">simpan</button>
        </form>
        <div class="card-header">
            <h4>Daftar Toko</h4>
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
            <div class="row">
                <div class="col-7">
                    <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#exampleModal">
                        <span class="text">+ Tambah</span>
                    </button>
                </div>
                <div class="col-5">
                    <form>
                        <div class="input-group">
                          <input type="text" id="cari" name="cari" class="form-control" placeholder="Search...">
                          <div class="input-group-btn">
                          </div>
                        </div>
                      </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Toko</th>
                            <th scope="col">Logo</th>
                            <th scope="col">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="alldata">
                        @forelse ($data as $index => $d)
                        <tr>
                            <td>{{$index + $data->firstItem()}}</td>
                            <td>{{ucfirst($d->toko)}}</td>
                            <td>
                                <img src="{{ asset('/storage/image/'.$d->logo) }}" class="rounded m-2" style="width: 100px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; ">
                            </td>
                            <td>
                                <div class="dropdown d-inline">
                                    <i class="fas fa-ellipsis-v cursor-pointer" style="cursor:pointer"
                                        id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false"></i>
                                    <form action="{{route('deletetoko', $d->id)}}" method="POST"
                                        onsubmit="return confirm('Apakah anda yakin akan menghapus data ini ?');">
                                        <div class="dropdown-menu" x-placement="bottom-start"
                                            style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                                            <a class="dropdown-item has-icon" href="{{route('detailtoko',$d->id)}}"><i
                                                    class="fas fa-info-circle"></i>Detail</a>
                                            <a class="dropdown-item has-icon" href="{{ route('edittoko', $d->id) }}"><i
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
                    <tbody id="content" class="searchdata"></tbody>
                </table>
            </div>
            <div style="margin-left:20px">
                {{ $data->links() }}
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#cari').on('keyup',function()
    {
        $value=$(this).val();
        if ($value) {
            $('.alldata').hide();
            $('.searchdata').show();
            
        }else{
            $('.alldata').show();
            $('.searchdata').hide();
        }

        $.ajax({
            type:'get',
            url:'{{route('cari-toko')}}',
            data:{'cari':$value},

            success:function(data){
                console.log(data);
                $('#content').html(data);
            }
        });
    })
</script>
@endsection
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Input Toko</h5>
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
