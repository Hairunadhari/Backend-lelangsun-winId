@extends('app.layouts')
@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Edit Toko</h4>
        </div>
        <form action="{{route('verify-pemenang', $data->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label>Nama Pemilik:</label>
                    <input type="text" class="form-control" value="{{ $data->nama_pemilik }}" name="toko" readonly>
                </div>
                <div class="form-group">
                    <label>Nominal Transfer:</label>
                    <input type="text" class="form-control" value="Rp. {{ number_format($data->nominal - $data->npl->harga_item,0,'.','.') }}" name="toko" readonly>
                </div>
                <div class="form-group">
                    <label>Tgl Transfer:</label>
                    <input type="text" class="form-control" value="{{ $data->tgl_transfer }}" name="toko" readonly>
                </div>
                <div class="form-group">
                    <label>Bukti Transfer:</label>
                    <br>
                    <a href="{{ asset('storage/image/'.$data->bukti) }}" target="_blank"><img src="{{ asset('storage/image/'.$data->bukti) }}" style="width: 150px;  box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin: 5px; padding: 0.25rem; border: 1px solid #dee2e6;"></a>
                </div>
                <div class="form-group">
                    <label>Pesan Notifikasi <span style="color: red">*</span></label>
                    <textarea class="form-control" name="pesan" required></textarea>
                </div>
            </div>
            <div class="card-footer text-right">
                <button class="btn btn-primary mr-1" type="submit">Verifikasi</button>
            </div>
        </form>
    </div>
@endsection
