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
<div class="section-body">
    <form action="{{route('updateproduk', $data->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h4>Edit Produk</h4>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Kategori Produk:</label>
                    <select class="form-control select2" name="kategoriproduk_id" id="kategoris" required>
                        @foreach ($kategori as $item)
                        <option value="{{ $item->id }}" {{ $item->id == $data->kategoriproduk->id ? 'selected' : '' }}>
                            {{ $item->kategori }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Nama Produk: </label>
                    <input type="text" class="form-control" value="{{ old('nama', $data->nama) }}" name="nama" required>
                </div>
                <div class="form-group">
                    <label>Keterangan:</label>
                    <textarea class="form-control" name="keterangan" required>{{$data->keterangan}}</textarea>
                </div>
                <div class="form-group">
                    <label>Harga: </label>
                    <input type="text" class="form-control" value="{{ old('harga', $data->harga) }}" name="harga" required
                        onkeyup="formatNumber(this)">
                </div>
                <div class="form-group">
                    <label>Stok: </label>
                    <input type="number" class="form-control" value="{{ old('stok', $data->stok) }}" name="stok" required>
                </div>
                <div class="form-group" id="inputberat">
                    <label>Berat (gram)</label>
                    <input type="number" class="form-control" value="{{$data->berat}}" name="berat" required
                        onkeyup="formatStok(this)">
                </div>
                <div class="form-group">
                    <label for="">Gambar Detail Produk:</label>
                    <br>
                    @foreach ($gambar as $item)
                    <div class="image-container">
                        <img class="ms-auto" src="{{ asset('storage/image/'.$item->gambar) }}"
                            style="width:150px; height:150px; box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px; margin-left: 10px; margin-bottom:10px;">
                        <button class="btn-delete" id="deletegambar" data-image-id="{{ $item->id }}">X</button>
                    </div>

                    @endforeach

                </div>
                <div class="form-group gambar">
                    <label>Ganti Gambar Detail Produk <small>(bisa pilih lebih dari satu gambar)</small></label>
                    <br>
                    <div class="input-images"></div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary mr-1" type="submit">Simpan</button>
                </div>
            </div>
        </div>

    </form>
</div>
</div>
<script>
    $('.input-images').imageUploader({
        imagesInputName: 'gambar',
        maxSize: 2 * 1024 * 1024,

    });
    $(document).on('click', '#deletegambar', function (e) {
        e.preventDefault();
        let id = $(this).data('image-id');
        let elementToRemove = $(this).closest('.image-container');
        console.log(id);
        $.ajax({
            method: 'post',
            url: '/superadmin/delete-gambar-produk/' + id,
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

    $(document).ready(function () {
        $('#tokos').on('change', function () {
            var tokosId = this.value;
            $('#kategoris').html('');
            $.ajax({
                url: "get-kategori-by-toko/" + tokosId,
                type: 'get',
                success: function (res) {
                    console.log(res);
                    $('#kategoris').html(
                        '<option value="" selected disabled>-- Pilih Kategori --</option>'
                        );
                    $.each(res, function (key, value) {
                        console.log(value);
                        $('#kategoris').append('<option value="' + value
                            .id + '">' + value.kategori + '</option>');
                    });
                }
            });
        });
    });

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
