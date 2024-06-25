@extends('app.layouts')
@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Form Verifikasi Pemenang Lelang </h4>
            @if ($data->status_pembayaran == 'Belum Bayar')
            <span class="badge bg-primary text-white">Belum Bayar</span>
            @else
            <span class="badge bg-success text-white">Lunas</span>
            @endif
        </div>
        <form action="{{route('superadmin.verify-pemenang', $data->id)}}" method="post" enctype="multipart/form-data" onsubmit="return confirm('Apakah anda yakin akan memVerifikasi data ini ?');">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label>Nama Pemilik:</label>
                    @if ($data->nama_pemilik == null)
                        <input type="text" class="form-control" value="Bidder Offline" name="toko" readonly>
                    @else
                        <input type="text" class="form-control" value="{{ $data->nama_pemilik }}" name="toko" readonly>
                    @endif
                </div>
                <div class="form-group">
                    <label>Nominal Transfer:</label>
                    @if ($data->npl == null)
                        <input type="text" class="form-control" value="Rp. {{ number_format($data->nominal,0,'.','.') }}" name="toko" readonly>
                    @else
                        <input type="text" class="form-control" value="Rp. {{ number_format($data->nominal - $data->npl->harga_item,0,'.','.') }}" name="toko" readonly>
                    @endif
                </div>
                @if ($data->npl !== null)
                <div class="form-group">
                    <label>Bukti Transfer:</label>
                    <br>
                        
                    <a href="{{ asset('storage/image/'.$data->bukti) }}" target="_blank"><img src="{{ asset('storage/image/'.$data->bukti) }}" style="width: 150px;  box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin: 5px; padding: 0.25rem; border: 1px solid #dee2e6;"></a>
                </div>
                <div class="form-group">
                    <label>Pesan Notifikasi <span style="color: red">*</span></label>
                    <textarea class="form-control" name="pesan" required></textarea>
                </div>
                @endif
            </div>
            @if ($data->status_pembayaran != 'Belum Bayar' || $data->nama_pemilik == 'Bidder Offline')
            <div class="card-footer text-right">
                <button class="btn btn-primary mr-1" type="submit">Verifikasi</button>
            </div>
            @endif
        </form>
    </div>
@endsection
