@extends('app.layouts')
@section('content')
<div class="section-body">
  <div class="invoice">
    <div class="invoice-print">
      <div class="row">
        <div class="col-lg-12">
          <div class="invoice-title">
            <h2>Pesanan</h2>
            <div class="invoice-number">Invoice ID : {{$tagihan->external_id}}</div>
          </div>
          <hr>
          <div class="row">
            <div class="col-md-6">
              <address>
                <strong>Info Pemesan:</strong><br>
                  {{$tagihan->user->name}}<br>
                  +62 {{$tagihan->user->no_telp}}<br>
                  {{$tagihan->user->alamat}}<br>
              </address>
            </div>
            <div class="col-md-6 text-md-right">
              <address>
                <strong>Info Pengiriman:</strong><br>
                {{$pengiriman->nama_pengirim}}<br>
                {{$pengiriman->pengiriman}}<br>
                {{$pengiriman->lokasi_pengiriman}}<br>
              </address>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 ">
              <address>
                <strong>Order Date:</strong><br>
                {{ date('d M Y', strtotime($tagihan->created_at)) }}<br>
              </address>
            </div>
            <div class="col-md-6 text-md-right">
              <address>
                <strong>Exp Date:</strong><br>
                {{ date('d M Y', strtotime($tagihan->exp_date)) }}<br>
              </address>
            </div>
            <div class="col-md-6 ">
              @if ($tagihan->status == 'PAID')
              <address>
                <strong>Metode Pembayaran:</strong><br>
                {{$tagihan->pembayaran->bank_code}}<br>
                {{$tagihan->pembayaran->email_user}}
              </address>
              @endif
            </div>
          </div>
        </div>
      </div>
      
      <div class="row mt-4">
        <div class="col-md-12">
          <div class="section-title">Produk Pesanan</div>
          <div class="table-responsive">
            <table class="table table-striped table-hover table-md">
              <tbody>
                <tr>
                  <th data-width="40">#</th>
                  <th>Produk</th>
                  <th class="text-center">Harga</th>
                  <th class="text-center">Quantity</th>
                  <th class="text-right">Total</th>
                </tr>
              <?php $no = 1 ?>
              @if ($hargaPromo == null)
                @foreach ($itemproduk as $ip)
                <tr>
                  <td>{{$no++}}</td>
                  <td>{{$ip->nama_produk}}</td>
                  <td class="text-center">Rp. {{number_format($ip->harga)}}</td>
                  <td class="text-center">{{$ip->qty}}</td>
                  <td class="text-right">Rp. {{number_format($ip->harga_x_qty)}}</td>
                </tr>
                @endforeach
              @else
                @foreach ($itemproduk as $ip)
                <tr>
                  <td>{{$no++}}</td>
                  <td>{{$ip->nama_produk}} (Promo)</td>
                  <td class="text-center">Rp. {{number_format($hargaPromo->total_diskon)}}</td>
                  <td class="text-center">{{$ip->qty}}</td>
                  <td class="text-right">Rp. {{number_format($ip->harga_x_qty)}}</td>
                </tr>
                @endforeach
              @endif
            </tbody>
          </table>
          </div>
          <div class="row mt-4">
            <div class="col-lg-8">
            </div>
            <div class="col-lg-4 text-right">
              <hr class="mt-2 mb-2">
              <div class="invoice-detail-item">
                <div class="invoice-detail-name">SubTotal</div>
                <div class="invoice-detail-value invoice-detail-value-lg">Rp. {{number_format($tagihan->total_pembayaran)}}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <hr>
  </div>
</div>
@endsection
