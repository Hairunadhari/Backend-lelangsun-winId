@extends('app.layouts')
@section('content')
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
            <button type="submit" class="btn btn-success mb-3">
                <span class="text">Simpan</span>
            </button>
            <div class="table-responsive">
                <table class="table table-bordered" id="tabel1">
                   <div class="row">
                    <div class="form-group col-6">
                        <label>Nama Promo</label>
                        <input type="text" class="form-control" name="promosi" required>
                    </div>
                    <div class="form-group col-6">
                        <label>Gambar Promo</label>
                        <input type="file" class="form-control" name="gambar" required>
                    </div>
                   </div>
                    <thead>
                        <label for="">Pilih Produk :</label>
                        <tr>
                            <th><input type="checkbox" id="pilihsemua"></th>
                            <th>Nama Produk</th>
                        </tr>
                    </thead>
                    <tbody>
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
        </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        // Ketika checkbox di atas tabel dengan id "tabel1" dicentang
        $("#pilihsemua").click(function(){
            // Ambil status checked dari checkbox di atasnya
            var isChecked = $(this).prop("checked");
            // Atur status checked dari semua checkbox di dalam tag <tbody> pada tabel "tabel1"
            $("#tabel1 tbody input[type='checkbox']").prop('checked', isChecked);
        });
    });
</script>
@endsection

