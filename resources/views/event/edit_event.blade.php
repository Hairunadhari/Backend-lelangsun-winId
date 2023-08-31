@extends('app.layouts')
@section('content')
<style>
    .reviews img {
        margin-bottom: 20px;
        margin-left: 20px;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
    }
</style>
    <div class="card">
        <div class="card-header">
            <h4>Edit Event</h4>
        </div>
        <form action="{{route('update-event', $data->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-6">
                        <label>Penyelenggara</label>
                        <input type="text" class="form-control" name="penyelenggara" value="{{$data->penyelenggara}}" >
                    </div>
                    <div class="form-group col-6">
                        <label>Judul</label>
                        <input type="text" class="form-control" name="judul" value="{{$data->judul}}">
                    </div>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea class="summernote-simple" placeholder="keterangan..."
                        name="deskripsi">{{$data->deskripsi}}</textarea>
                </div>
                <div class="form-group">
                    <label>Alamat Lokasi</label>
                    <textarea class="form-control" name="alamat_lokasi">{{$data->alamat_lokasi}}</textarea>
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label>Jenis</label>
                        <select class="form-control selectric" name="jenis" >
                            <option value="Offline" <?= ($data->jenis == 'Offline' ? 'selected' : '')?>>Offline</option>
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label>Tiket</label>
                        <select id="tiket" class="form-control selectric" name="tiket" onchange="toggleDiv(this.value)">
                            <option value="Berbayar" <?= ($data->tiket == 'Berbayar' ? 'selected' : '')?>>Berbayar</option>
                            <option value="Gratis" <?= ($data->tiket == 'Gratis' ? 'selected' : '')?>>Gratis</option>
                        </select>
                    </div>
                </div>
                <div id="editinpharga" style="display: none;">
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="text" class="form-control" name="harga" onkeyup="formatNumber(this)" value="{{$data->harga}}">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label>Tanggal Mulai</label>
                        <input type="date" class="form-control" name="tgl_mulai" value="{{$data->tgl_mulai}}">
                    </div>
                    <div class="form-group col-6">
                        <label>Tanggal Selesai</label>
                        <input type="date" class="form-control" name="tgl_selesai" value="{{$data->tgl_selesai}}">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label>Link Meeting</label>
                        <input type="text" class="form-control" name="link" value="{{$data->link}}">
                    </div>
                    <div class="form-group col-6">
                        <label>Link Lokasi</label>
                        <input type="text" class="form-control" name="link_lokasi" value="{{$data->link_lokasi}}">
                    </div>
                </div>
                <div class="form-group">
                    <label>Image <small>(png, jpg, jpeg)</small></label>
                    <input type="file" class="form-control" name="gambar"  id="gambar">
                <div id="preview" class="mt-3"></div>
                </div>
                 <div class="form-group" >
                        <label for="">Poster detail event sebelumnya:</label>
                        <br>
                        @foreach ($data->detail_gambar_event as $item)
                        <img class="ms-auto" src="{{ asset('storage/image/'.$item->gambar) }}"
                            style="width:100px;box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;">
                        @endforeach
                    </div>
                <div class="form-group">
                        <label>Poster Detail Event <small>(bisa pilih lebih dari satu gambar | format gambar: png, jpg, jpeg | disarankan: width 900px, height 470px) </small></label>
                        <input type="file" class="form-control" name="poster[]" id="gambars"  multiple>
                    </div>
                    <div id="previews" class="reviews"></div>
            </div>
            <div class="card-footer text-right">
                <button class="btn btn-primary mr-1" type="submit">Submit</button>
            </div>
        </form>
    </div>
<script>
    
    window.onload = function () {
        const selectElement = document.getElementById("tiket");
        const editinpharga = document.getElementById("editinpharga");
        
        if (selectElement.value == "Berbayar") {
            editinpharga.style.display = "block";
        } else {
            editinpharga.style.display = "none";
        }
    };
    
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
    

    function toggleDiv(value) {
        const editinpharga = document.getElementById("editinpharga");
        if (value == "Berbayar") {
            editinpharga.style.display = "block";
        } else {
            editinpharga.style.display = "none";
        }
    };

    function formatNumber(input) {
        // Menghilangkan karakter selain angka
        var num = input.value.replace(/[^0-9]/g, '');

        // Memformat angka menjadi format ribuan dan desimal
        var formattedNum = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(num);

        // Memasukkan nilai format ke dalam input
        input.value = formattedNum;
    }

    function previewsImages() {
        var previews = document.querySelector('#previews');

        // Hapus semua elemen child di dalam elemen #previews
        while (previews.firstChild) {
            previews.removeChild(previews.firstChild);
        }

        if (this.files) {
            [].forEach.call(this.files, readAndPreviews);
        }

        function readAndPreviews(file) {
            if (!/\.(jpe?g|png|jpg)$/i.test(file.name)) {
                alert("Hanya file gambar dengan ekstensi .jpeg, .jpg, .png, yang diperbolehkan.");
                document.querySelector('#gambars').value = '';
                return;
            }

            var reader = new FileReader();
            reader.addEventListener("load", function () {
                var image = new Image();
                image.width = 200;
                image.title = file.name;
                image.src = this.result;
                previews.appendChild(image);
            }, false);
            reader.readAsDataURL(file);
        }
    }

    document.querySelector('#gambars').addEventListener("change", previewsImages);
</script>
@endsection
