@extends('app.layouts')
@section('content')
<style>
    .scroll{
        height:500px;
        overflow: scroll;
    }
</style>
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Input Promo Produk</h4>
        </div>
        <div class="card-body">
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{ session('error') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            <form action="{{route('addpromosi')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="table-responsive">
                    <div class="form-group">
                        <label>Nama Promo <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="promosi" required>
                    </div>
                    <div class="form-group">
                        <label>Deksripsi Promo <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="deskripsi" required>
                    </div>
                    <div class="form-group">
                        <label>Diskon <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="diskon" required onkeyup="formatPromo(this)">
                    </div>
                    <div class="form-group">
                        <label>Tanggal Mulai <span style="color: red">*</span></label>
                        <input type="date" class="form-control" name="tanggal_mulai">
                    </div>
                    <div class="form-group">
                        <label>Tanggal Selesai <span style="color: red">*</span></label>
                        <input type="date" class="form-control" name="tanggal_selesai">
                    </div>
                    <div class="form-group">
                        <label>Gambar Promo <small>(png, jpg, jpeg)</small><span style="color: red">*</span></label>
                        <input type="file" class="form-control" accept=".jpg, .png, .jpeg" name="gambar" required id="gambar">
                        <div id="preview" class="mt-2"></div>
                    </div>
                    <label for="">Pilih Produk <span style="color: red">*</span></label>
                    <div class="scroll">
                        <table class="table table-bordered" id="tabel1">
                            <thead style="position: sticky; top: 0; background-color: white;">
                                <tr>
                                    <th><input type="checkbox" id="pilihsemua"></th>
                                    <th>Nama Produk</th>
                                    <th>Cover Produk</th>
                                </tr>
                            </thead>
                            <tbody >
                                @if (Auth::user()->role->role == 'Super Admin')
                                    @foreach ($produk as $p)
                                    <tr>
                                        <td><input type="checkbox" name="produk_id[]" value="{{$p->id}}"></td>
                                        <td>{{$p->nama}}</td>
                                        <td>
                                            <img src="{{ asset('/storage/image/'.$p->thumbnail) }}" class="rounded m-2" style="width: 100px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; ">
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    @foreach ($produk_berdasarkan_toko as $p)
                                    <tr>
                                        <td><input type="checkbox" name="produk_id[]" value="{{$p->id}}"></td>
                                        <td>{{$p->nama}}</td>
                                        <td>
                                            <img src="{{ asset('/storage/image/'.$p->thumbnail) }}" class="rounded m-2" style="width: 100px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; ">
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-success mt-3" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        // Ketika checkbox di atas tabel dengan id "tabel1" dicentang
        $("#pilihsemua").click(function () {
            // Ambil status checked dari checkbox di atasnya
            var isChecked = $(this).prop("checked");
            // Atur status checked dari semua checkbox di dalam tag <tbody> pada tabel "tabel1"
            $("#tabel1 tbody input[type='checkbox']").prop('checked', isChecked);
        });
    });

    function formatPromo(input) {
        // Menghilangkan karakter selain angka
        var num = input.value.replace(/[^0-9]/g, '');
        
        // Memastikan angka tidak melebihi 100 (maksimum 3 digit)
        if (num > 100) {
            num = 100;
        }
      
        // Menambahkan tanda persen (%) di belakang angka
        var formattedNum = num + '%';
      
        // Memasukkan nilai format ke dalam input
        input.value = formattedNum;
    }   

    function previewImages() {
        var preview = document.querySelector('#preview');

        // Hapus semua elemen child di dalam elemen #preview
        while (preview.firstChild) {
            preview.removeChild(preview.firstChild);
        }

        if (this.files) {
            [].forEach.call(this.files, readAndPreview);
        }

        function readAndPreview(file) {
            if (!/\.(jpe?g|png)$/i.test(file.name)) {
                alert(file.name + " format tidak sesuai");
                document.querySelector('#gambar').value = '';
                preview.removeChild(preview.firstChild);
                return;
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

</script>
@endsection
