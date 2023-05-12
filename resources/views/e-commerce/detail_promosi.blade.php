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
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Gambar Produk</th>
                        </tr>
                    </thead>
                    @php
                    $no = 1;
                    @endphp
                    <tbody>
                        @foreach ($produkPromo as $item)
                        <tr>
                            <td>{{$no++}}</td>
                            <td>{{$item->produk->nama}}</td>
                            <td>
                                <img src="{{ asset('/storage/image/'.$item->produk->thumbnail) }}" class="rounded m-2" style="width: 100px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; ">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- <div class="row">{{ $produk->links() }}
        </div> --}}
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
