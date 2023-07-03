@extends('app.layouts')
@section('content')
<style>
    .my-custom-scrollbar {
        height:500px;
        overflow: scroll;
    }
</style>
<div class="section-body">
    <form action="{{route('updatepromosi', $data->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h4>Edit Promo Produk</h4>
            </div>
            <div class="card-body">
                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>{{ session('error') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                <div class="table-responsive">
                    <div class="form-group">
                        <label>Nama Promo</label>
                        <input type="text" class="form-control" name="promosi"
                            value="{{old('promosi', $data->promosi)}}">
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <input type="text" class="form-control" name="deskripsi" value="{{old('deskripsi', $data->deskripsi)}}">
                    </div>
                    <div class="form-group">
                        <label>Diskon</label>
                        <input type="text" class="form-control" name="diskon" value="{{old('diskon', $data->diskon)}}" onkeyup="formatPromo(this)">
                    </div>
                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <input type="date" class="form-control" name="tanggal_mulai" value="{{ $data->tanggal_mulai }}">
                    </div>                    
                    <div class="form-group">
                        <label>Tanggal Selesai</label>
                        <input type="date" class="form-control" name="tanggal_selesai" value="{{$data->tanggal_selesai}}">
                    </div>
                    <label for="">Pilih Produk :</label>
                    <div class="table-wrapper-scroll-y my-custom-scrollbar">
                        <table class="table table-bordered" id="tabel1">
                            <thead style="position: sticky; top: 0; background-color: white;">
                                <tr>
                                    <th><input type="checkbox" id="pilihsemua"></th>
                                    <th>Nama Produk</th>
                                    <th>Cover Produk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($produk as $p)
                                <tr>
                                    <td><input type="checkbox" name="produk_id[]" value="{{$p->id}}"
                                            {{isset($produkTerpilih[$p->id])?"checked":""}}></td>
                                    <td>{{strtoupper($p->nama)}}</td>
                                    <td><img class="ms-auto" src="{{ asset('storage/image/'.$p->thumbnail) }}" style="width:100px"></td>
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Media</h4>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Gambar Promo</label>
                    <br>
                    <img class="ms-auto" src="{{ asset('storage/image/'.$data->gambar) }}" style="width:200px">
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Ganti Gambar Promo</label>
                    <input type="file" class="form-control mb-2" name="gambar" id="gambar">
                    <div id="preview"></div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button class="btn btn-success mt-3" type="submit">Simpan</button>
            </div>
        </div>
    </form>

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

    document.querySelector('#resetButton').addEventListener('click', function () {
        document.querySelector('#preview').innerHTML = '';
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


</script>
@endsection
