@extends('app.layouts')
@section('content')
<style>
    nav {
        margin-left: auto;
    }

</style>
<div class="section-header">
    <h1>Data E-commerce</h1>
</div>
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Detail Promo Produk</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="tabel1">
                    <div class="form-group">
                        <label>Nama Promo</label>
                        <input type="text" class="form-control" name="promosi" value="{{$data->promosi}}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <input type="text" class="form-control" name="promosi" value="{{$data->deskripsi}}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Diskon</label>
                        <input type="text" class="form-control" name="promosi" value="{{$data->diskon}}%" readonly>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <input type="text" class="form-control" name="promosi" value="{{$data->tanggal_mulai}}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Selesai</label>
                        <input type="text" class="form-control" name="promosi" value="{{$data->tanggal_selesai}}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <input type="text" class="form-control" name="promosi" value="{{$data->status}}" readonly>
                    </div>
                    <thead style="position: sticky; top: 0; background-color: white;">
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Gambar Produk</th>
                            <th>Harga Produk</th>
                            <th>Harga Diskon</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produkPromo as $produk => $item)
                        <tr>
                            <td>{{$produk + $produkPromo->firstItem()}}</td>
                            <td>{{$item->produk->nama}}</td>
                            <td>
                                <img src="{{ asset('/storage/image/'.$item->produk->thumbnail) }}" class="rounded m-2" style="width: 100px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; ">
                            </td>
                            <td><s>Rp. {{number_format($item->produk->harga)}}</s></td>
                            <td>Rp. {{number_format($item->total_diskon)}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">{{ $produkPromo->links() }}
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
            <img class="ms-auto" src="{{ asset('storage/image/'.$data->gambar) }}" style="width:700px">
        </div>
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

</script>
@endsection
