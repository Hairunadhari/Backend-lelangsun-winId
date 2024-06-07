@extends('app.layouts')
@section('content')
<style>
    .image-container {
        position: relative;
        display: inline-block;
    }
     .btn-delete {
        position: absolute;
        top: 1px;
        right: 1px;
        background-color: rgba(0, 0, 0, .5);
        /* Ganti warna sesuai kebutuhan */
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
    }
</style>
    <div class="card">
        <div class="card-header">
            <h4>Edit Event</h4>
        </div>
        <form action="{{route('superadmin.update-event', $data->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-6">
                        <label>Penyelenggara</label>
                        <input type="text" class="form-control" name="penyelenggara" value="{{$data->penyelenggara}}" required>
                    </div>
                    <div class="form-group col-6">
                        <label>Judul</label>
                        <input type="text" class="form-control" name="judul" value="{{$data->judul}}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea class="summernote-simple" placeholder="keterangan..."
                        name="deskripsi" required>{{$data->deskripsi}}</textarea>
                </div>
                <div class="form-group">
                    <label>Alamat Lokasi</label>
                    <textarea class="form-control" name="alamat_lokasi" required>{{$data->alamat_lokasi}}</textarea>
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label>Jenis</label>
                        <select class="form-control selectric" name="jenis" required>
                            <option value="Offline" <?= ($data->jenis == 'Offline' ? 'selected' : '')?>>Offline</option>
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label>Tiket</label>
                        <select id="tiket" class="form-control selectric" required name="tiket" onchange="toggleDiv(this.value)">
                            <option value="Berbayar" <?= ($data->tiket == 'Berbayar' ? 'selected' : '')?>>Berbayar</option>
                            <option value="Gratis" <?= ($data->tiket == 'Gratis' ? 'selected' : '')?>>Gratis</option>
                        </select>
                    </div>
                </div>
                <div id="editinpharga" style="display: none;">
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="text" required class="form-control" name="harga" onkeyup="formatNumber(this)" value="{{$data->harga}}">
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
                 <div class="form-group" >
                        <label for="">Poster Service Sebelumnya:</label>
                        <br>
                        @foreach ($data->detail_gambar_event as $item)
                        <div class="image-container">
                            <img class="ms-auto" src="{{ asset('storage/image/'.$item->gambar) }}"
                                style="width:250px; height:150px; box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px; margin-left: 10px; margin-bottom:10px;">
                            <button class="btn-delete" id="deletegambar" data-image-id="{{ $item->id }}">X</button>
                        </div>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <label>Poster Service <small>(disarankan: width 900px, height 470px) </small><span
                                style="color: red">*</span></label>
                        <div class="input-images"></div>
                    </div>
            </div>
            <div class="card-footer text-right">
                <button class="btn btn-primary mr-1" type="submit">Submit</button>
            </div>
        </form>
    </div>
<script>
      $('.input-images').imageUploader({
        imagesInputName: 'poster',
        maxSize: 2 * 1024 * 1024,

    });
    $(document).on('click', '#deletegambar', function (e) {
        e.preventDefault();
        let id = $(this).data('image-id');
        let elementToRemove = $(this).closest('.image-container');
        console.log(id);
        $.ajax({
            method: 'post',
            url: '/superadmin/delete-gambar-event/' + id,
            data: {
                _method: 'delete',
            },
            success: function (res) {
                console.log(res);
                elementToRemove.remove();
                iziToast.success({
                    title: 'Notifikasi',
                    message: res.success,
                    position: 'topRight'
                });
            }
        });
    });

    window.onload = function () {
        const selectElement = document.getElementById("tiket");
        const editinpharga = document.getElementById("editinpharga");
        
        if (selectElement.value == "Berbayar") {
            editinpharga.style.display = "block";
        } else {
            editinpharga.style.display = "none";
        }
    };
    
    function toggleDiv(value) {
        const editinpharga = document.getElementById("editinpharga");
        const atr = editinpharga.querySelectorAll("input[required]");
        if (value == "Berbayar") {
            editinpharga.style.display = "block";
        } else {
            editinpharga.style.display = "none";
            atr.forEach(input => {
                input.removeAttribute("required");
            });
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

</script>
@endsection
