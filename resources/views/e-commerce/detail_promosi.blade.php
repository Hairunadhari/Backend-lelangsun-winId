@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Detail Promo Produk</h4>
        </div>
        <div class="card-body">
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
                <label for="">Produk :</label>
                <table class="table table-striped" id="tabeldetailpromosi">
                    <thead >
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Cover Produk</th>
                            <th>Harga Produk</th>
                            <th>Harga Diskon</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
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

    $(document).ready(function () {
        $('#tabeldetailpromosi').DataTable({
            processing: false,
            ordering: false,
            fixedColumns: true,
            searching: false,
            ajax: '{{ url()->current() }}',
            columns: [{
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "produk.nama",
                },
                {
                    data: "produk.thumbnail",
                    render: function (data) {
                        return '<img src="/storage/image/' + data +
                            '"style="width: 100px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin:5px; ">';
                    },
                },
                {
                    data: "produk.harga",
                    render: function (data) {
                        return '<s>' + 'Rp.' +  data + '</s>';
                    }
                },
                {
                    data: "total_diskon",
                    render: $.fn.dataTable.render.number(',', '.', 0, 'Rp. '),
                }
            ],
        });
    });
    
</script>
@endsection
{{-- // <s>Rp. {{number_format($item->produk->harga)}}</s> --}}
