@extends('app.layouts')
@section('content')
@if (Auth::user()->role->role == 'Super Admin')
<div class="section-body">
  <div class="invoice">
      <div class="invoice-print">
          <div class="row">
              <div class="col-lg-12">
                  <div class="invoice-title">
                      <h2>Detail Pesanan</h2>
                      <div class="invoice-number">Invoice ID : {{$invoice->no_invoice}}</div>
                  </div>
                  <hr>
                  <div class="row">
                      <div class="col-md-6">
                          <address>
                              <strong>Info Pemesan:</strong><br>
                              Nama :{{$invoice->order_name}}<br>
                              No Telp : +62 {{$invoice->notelp}}<br>
                              Alamat : {{$invoice->alamat}}<br>
                          </address>
                      </div>
                      <div class="col-md-6 text-md-right">
                          <address>
                              <strong>Info Pengiriman:</strong><br>
                              {{-- {{$pengiriman->nama_pengirim}}<br>
                              {{$pengiriman->pengiriman}}<br>
                              {{$pengiriman->lokasi_pengiriman}}<br> --}}
                          </address>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-6 ">
                          <address>
                              <strong>Order Date:</strong><br>
                              {{ date('d M Y', strtotime($invoice->created_at)) }}<br>
                          </address>
                      </div>
                      <div class="col-md-6 text-md-right">
                          <address>
                              <strong>Exp Date:</strong><br>
                              {{ date('d M Y', strtotime($invoice->exp_date_invoice)) }}<br>
                          </address>
                      </div>
                      <div class="col-md-6 ">
                          @if ($invoice->status == 'PAID')
                          <address>
                              <strong>Metode Pembayaran:</strong><br>
                              {{$invoice->pembayaran->bank_code}}<br>
                              {{$invoice->pembayaran->email_user}}
                          </address>
                          @endif
                      </div>
                  </div>
              </div>
          </div>

          <div class="row mt-4">
              <div class="col-md-12">
                  <div class="section-title">List Produk Pesanan</div>
                  <div class="table-responsive">
                      <table class="table table-bordered">
                          <thead>
                              <tr>
                                  <th>Nama Toko</th>
                                  <th>Nama Produk</th>
                                  <th class="text-center">Harga</th>
                                  <th class="text-center">Quantity</th>
                                  <th class="text-right">Total</th>
                              </tr>
                          </thead>
                          <tbody>
                            @foreach ($invoice->invoice_store as $item)
                              <tr>
                                    
                                <td>{{$item->name_toko}}</td>
                                <td>{{$item->nama_produk}}</td>
                                <td class="text-center">Rp {{number_format($item->harga,0,',','.')}}
                                  @if ($item->promo_diskon != null)
                                    <label class="badge badge-sm badge-dark"><i class="fa fa-tag"></i> Diskon {{$item->promo_diskon}}%</label>
                                    @endif
                                  </td>
                                  <td class="text-center">{{$item->qty}}</td>
                                  <td class="text-right">Rp {{ number_format($item->harga_x_qty, 0, ',', '.') }}</td>

                                </tr>
                                @endforeach
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
                              <div class="invoice-detail-value invoice-detail-value-lg">Rp
                                  {{number_format($invoice->sub_total,0,',','.')}}</div>
                             
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <hr>
  </div>
</div>
@else

<div class="section-body">
    <div class="invoice">
        <div class="invoice-print">
            <div class="row">
                <div class="col-lg-12">
                    <div class="invoice-title">
                        <h2>Detail Pesanan</h2>
                        <div class="invoice-number">Invoice ID : {{$invoice->external_id}}</div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <address>
                                <strong>Info Pemesan:</strong><br>
                                Nama :{{$invoice->nama_pembeli}}<br>
                                No Telp : +62 {{$invoice->no_telp}}<br>
                                Alamat : {{$invoice->alamat}}<br>
                            </address>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <address>
                                <strong>Info Pengiriman:</strong><br>
                                {{-- {{$pengiriman->nama_pengirim}}<br>
                                {{$pengiriman->pengiriman}}<br>
                                {{$pengiriman->lokasi_pengiriman}}<br> --}}
                            </address>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 ">
                            <address>
                                <strong>Order Date:</strong><br>
                                {{ date('d M Y', strtotime($invoice->created_at)) }}<br>
                            </address>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <address>
                                <strong>Exp Date:</strong><br>
                                {{ date('d M Y', strtotime($invoice->exp_date)) }}<br>
                            </address>
                        </div>
                        <div class="col-md-6 ">
                            @if ($invoice->status == 'PAID')
                            <address>
                                <strong>Metode Pembayaran:</strong><br>
                                {{$invoice->pembayaran->bank_code}}<br>
                                {{$invoice->pembayaran->email_user}}
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
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$invoice->nama_produk}}</td>
                                    <td class="text-center">Rp {{number_format($invoice->harga,0,',','.')}}
                                        @if ($invoice->promo_diskon != 0)
                                        <span class="badge badge-sm badge-dark"><i class="fa fa-tag"></i> Diskon {{$invoice->promo_diskon}}%</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{$invoice->qty}}</td>
                                    <td class="text-right">Rp {{number_format($invoice->harga_x_qty,0,',','.')}}</td>
                                </tr>
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
                                <div class="invoice-detail-value invoice-detail-value-lg">Rp
                                    {{number_format($invoice->harga_x_qty,0,',','.')}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
    </div>
</div>

@endif
@endsection
