@extends('app.layouts')
@section('content')
<style>
</style>
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Detail Produk</h4>
        </div>
        <div class="card-body">
            <form>
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" class="form-control" value="{{$data->pembelian_npl->nama_pemilik}}" readonly>
                </div>
                <div class="form-group">
                    <label>Kode NPL</label>
                    <input type="text" class="form-control" value="{{$data->kode_npl}}" readonly>
                </div>
                <div class="form-group">
                    <label>Event</label>
                    <input type="text" class="form-control" value="{{$data->event_lelang->judul}} ({{$data->event_lelang->waktu}}) (kategori: {{$data->event_lelang->kategori_barang->kategori}})" readonly>
                </div>
                <div class="form-group">
                    <label>Waktu Pembelian</label>
                    <input type="text" class="form-control" value="{{$data->pembelian_npl->created_at->format('Y-M-d')}}" readonly>
                </div>
                <div class="form-group">
                    <label>No Rekening</label>
                    <input type="text" class="form-control" value="{{$data->pembelian_npl->no_rek}}" readonly>
                </div>
                <div class="form-group">
                    <label>Type Transaksi</label>
                    <input type="text" class="form-control" value="{{$data->pembelian_npl->type_transaksi}}" readonly>
                </div>
                <div class="form-group">
                    <label>Bukti Transfer</label>
                    <br>
                    @if ($data->pembelian_npl->bukti == null)
                        <span>-</span>
                    @else
                    <img class="d-block" style="width:200px; padding:0.25rem; border:1px solid #dee2e6;" src="{{ asset('storage/image/'.$data->pembelian_npl->bukti) }}" alt="">
                        
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
