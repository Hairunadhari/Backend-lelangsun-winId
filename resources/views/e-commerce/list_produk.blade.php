@extends('app.layouts')
@section('content')
<style>
    .review img{
        margin-bottom: 20px;
        margin-left: 20px;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
    }
</style>
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="w-100">Daftar Produk</h4>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#produkmodal">
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
                        <table class="table table-striped" id="t-produk">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Produk</th>
                                    <th scope="col">Cover Produk</th>
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
<script>
    $(document).ready(function () {
        $('#t-produk').DataTable({
            // responsive: true,
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
                    data: "nama",
                },
                {
                    data: "thumbnail",
                    render: function (data) {
                        return '<img src="/storage/image/' + data +
                            '"style="width: 100px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin:5px; ">';
                    },
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
                    var deleteUrl = '/deleteproduk/' + data.id;
                    var editUrl = '/editproduk/' + data.id;
                    var detailUrl = '/detailproduk/' + data.id;
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

    function formatStok(input) {
      // Menghilangkan karakter selain angka
      var num = input.value.replace(/[^0-9]/g, '');
      
      input.value = num;
    }

</script>
@endsection
<!-- Modal -->
<div class="modal fade" id="produkmodal" tabindex="-1" role="dialog" aria-labelledby="produkLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="produkLabel">Form Input Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('addproduk')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Toko:</label>
                        <select class="form-control" name="toko_id" required>
                            <option>Pilih Toko</option>
                            @foreach ($dataToko as $item)
                            <option value="{{ $item->id }}">{{ $item->toko }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kategori Produk</label>
                        <select class="form-control" name="kategoriproduk_id" required>
                            <option>Pilih Kategori</option>
                            @foreach ($dataKategoriproduk as $item)
                            <option value="{{ $item->id }}">{{ $item->kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input type="text" class="form-control" name="nama" required >
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea class="form-control" name="keterangan" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="text" class="form-control" name="harga" onkeyup="formatNumber(this)" required>
                    </div>
                    <div class="form-group">
                        <label>Stok</label>
                        <input type="number" class="form-control" name="stok" required onkeyup="formatStok(this)" >
                    </div>
                    <div class="form-group">
                        <label>Link Video:</label>
                        <input type="text" class="form-control" name="video" required placeholder="">
                    </div>
                    <div class="form-group">
                        <label>Cover Produk:</label>
                        <input type="file" class="form-control" name="thumbnail" required placeholder="">
                    </div>
                    <div class="form-group">
                        <label>Gambar Detail Produk <small>(bisa pilih lebih dari satu gambar)</small></label>
                        <input type="file" class="form-control" name="gambar[]" id="gambar" required multiple>
                    </div>
                        <div id="preview" class="review"></div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-warning" id="resetButton">Reset</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>