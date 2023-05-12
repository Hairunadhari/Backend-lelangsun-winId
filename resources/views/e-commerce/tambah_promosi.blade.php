@extends('app.layouts')
@section('content')
<style>
    .my-custom-scrollbar {
        position: relative;
        height: 300px;
        overflow: auto;
    }

    .table-wrapper-scroll-y {
        display: block;
    }

</style>
<div class="section-header">
    <h1>Data E-commerce</h1>
</div>
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Input Promo Produk</h4>
        </div>
        <div class="card-body">
            <form action="{{route('addpromosi')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="table-responsive">
                    <div class="form-group">
                        <label>Nama Promo</label>
                        <input type="text" class="form-control" name="promosi" required>
                    </div>
                    <div class="form-group">
                        <label>Gambar Promo</label>
                        <input type="file" class="form-control" name="gambar" required>
                    </div>
                    <label for="">Pilih Produk :</label>
                    <div class="table-wrapper-scroll-y my-custom-scrollbar">
                        <table class="table table-bordered" id="tabel1">
                            <thead style="position: sticky; top: 0; background-color: white;">
                                <tr>
                                    <th><input type="checkbox" id="pilihsemua"></th>
                                    <th>Nama Produk</th>
                                </tr>
                            </thead>
                            <tbody >
                                @forelse ($produk as $p)
                                <tr>
                                    <td><input type="checkbox" name="produk_id[]" value="{{$p->id}}"></td>
                                    <td>{{$p->nama}}</td>
                                </tr>
                                @empty
                                @endforelse
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

</script>
@endsection
