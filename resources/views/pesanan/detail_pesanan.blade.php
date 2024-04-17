@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="invoice">
        <div class="invoice-print">
            <div class="row">
                <div class="col-lg-12">
                    <div class="invoice-title">
                        <h2>Detail Pesanan</h2>
                        <div class="invoice-number">No Invoice : {{$order->no_invoice}}</div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <address>
                                <strong>Info Pemesan:</strong><br>
                                Nama : {{$order->nama_user}}<br>
                                No Telp : {{$order->no_telephone_user}}<br>
                                Alamat : {{$order->detail_alamat_user}}<br>
                            </address>
                        </div>
                        <div class="col-md-6 text-md-right">
                            @if ($order->status == 'PAID')
                            <address>
                                <strong>Metode Pembayaran:</strong><br>
                                {{$order->bank_code}}<br>
                                {{$order->email_user}}
                            </address>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 ">
                            <address>
                                <strong>Order Date:</strong><br>
                                {{ date('d M Y', strtotime($order->created_at)) }}<br>
                            </address>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <address>
                                <strong>Exp Date:</strong><br>
                                {{ date('d M Y', strtotime($order->exp_date_invoice)) }}<br>
                            </address>
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
                                @foreach ($orderItems as $item)
                                <tr>
                                    <td>{{$item->nama_produk}}</td>
                                    <td class="text-center">Rp {{number_format($item->harga,0,',','.')}}
                                        @if ($item->promo_diskon != 0)
                                        <span class="badge badge-sm badge-dark"><i class="fa fa-tag"></i> Diskon {{$item->promo_diskon}}%</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{$item->qty}}</td>
                                    <td class="text-right">Rp {{number_format($item->total_harga_item,0,',','.')}}</td>
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
                                    {{number_format($order->sub_total,0,',','.')}}</div>
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
