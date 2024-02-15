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
            <h4>Form Update Lot</h4>
        </div>
        <div class="card-body">
            <h5>Detail Event</h5>
            <ul>
                <li>Nama Event : {{$lot->event_lelang->judul}}</li>
                <li>Kategori Event : {{$lot->event_lelang->kategori_barang->kategori}}</li>
                <li>Deskripsi Event : {!! $lot->event_lelang->deskripsi !!}</li>
                <li>Alamat Event : {{$lot->event_lelang->alamat}}</li>
                <li>Waktu/Tanggal Event : {{$lot->event_lelang->waktu}}</li>
            </ul>
            <form action="{{route('superadmin.update-lot',$lot->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="table-responsive">
                    <label for="">List Barang <span style="color: red">*</span></label>
                    <input type="hidden" value="{{$lot->event_lelang->id}}" name="event_id">
                    <input type="hidden" value="{{$lot->event_lelang->kategori_barang->kelipatan_bidding}}" name="harga_awal">
                    <input type="hidden" value="{{$lot->event_lelang->waktu}}" name="waktu_from_event">
                    <div class="scroll">
                        <table class="table table-bordered" id="tabel1">
                            <thead style="position: sticky; top: 0; background-color: white;">
                                <tr>
                                    <th><input type="checkbox" id="pilihsemua"></th>
                                    <th>Nama Barang</th>
                                    <th>Harga Awal Barang</th>
                                </tr>
                            </thead>
                            <tbody >
                                @foreach ($baranglelang as $p)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="barang_id[]" value="{{ $p->id }}" {{ isset($barangTerpilih[$p->id]) ? 'checked' : '' }}>
                                    </td>
                                    <td>
                                        {{ $p->barang }}
                                        <input type="hidden" value="{{$p->barang}}" name="nama_barang[]">
                                    </td>
                                    <td>
                                        @if(isset($barangTerpilih[$p->id]))
                                            <input type="text" class="form-control" name="harga_awal[]" onkeyup="formatNumber(this)" value="{{ $barangTerpilih[$p->id] }}">
                                        @else
                                            <input type="text" class="form-control"  name="harga_awal[]" onkeyup="formatNumber(this)" style="display: none">
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                

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
     function formatNumber(input) {
        // Menghilangkan karakter selain angka
        var num = input.value.replace(/[^0-9]/g, '');

        // Memformat angka menjadi format ribuan dan desimal
        var formattedNum = new Intl.NumberFormat('id-ID', {
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(num);

        // Memasukkan nilai format ke dalam input
        input.value = formattedNum;
    }
   $(document).ready(function () {
    // Ketika checkbox di atas tabel dengan id "pilihsemua" dicentang
    $("#pilihsemua").click(function () {
        // Ambil status checked dari checkbox di atasnya
        var isChecked = $(this).prop("checked");
        // Atur status checked dari semua checkbox di dalam tag <tbody> pada tabel "tabel1"
        $("#tabel1 tbody input[type='checkbox']").prop('checked', isChecked);

        // Tampilkan atau sembunyikan input harga awal dan tambahkan atribut required
        $("#tabel1 tbody input[type='checkbox']").each(function () {
            var isChecked = $(this).prop("checked");
            var hargaAwalInput = $(this).closest("tr").find("input[name='harga_awal[]']");

            if (isChecked) {
                hargaAwalInput.show().prop('required', true);
            } else {
                hargaAwalInput.hide().prop('required', false).val('');
            }
        });
    });

    // Ketika terjadi perubahan pada kotak centang di dalam tabel
    $("#tabel1 tbody input[type='checkbox']").change(function () {
        var isChecked = $(this).prop("checked");
        var hargaAwalInput = $(this).closest("tr").find("input[name='harga_awal[]']");

        if (isChecked) {
            hargaAwalInput.show().prop('required', true);
        } else {
            hargaAwalInput.hide().prop('required', false).val('');
        }
    });
});



</script>
@endsection
