@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Edit Kategori Lelang</h4>
            </div>
            <form action="{{route('update-kategori-lelang', $data->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    @if($errors->has('kategori'))
                    <div class="alert alert-danger alert-dismissible text-center fade show" role="alert">
                        <strong>{{ $errors->first('kategori') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <div class="form-group">
                        <label>Kategori</label>
                        <input type="text" class="form-control" value="{{  $data->kategori }}" name="kategori">
                    </div>
                     <div class="form-group">
                        <label>Kelipatan Bidding</label>
                        <input type="text" class="form-control" name="kelipatan_bidding" value="{{ $data->kelipatan_bidding }}">
                    </div>
                    <div class="form-group">
                        <label>Harga NPL</label>
                        <input type="text" class="form-control" name="harga_npl" onkeyup="hargaNPL(this)" value="{{ $data->harga_npl }}">
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary mr-1" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function hargaNPL(input) {
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
