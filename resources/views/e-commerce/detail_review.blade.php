@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Detail Review / Reply</h4>
        </div>
        <div class="card-body">
            <div class="tickets">
                <div class="ticket-content">
                    <div class="ticket-header">
                        <div class="ticket-sender-picture img-shadow">
                            <img src="{{('/storage/image/'. $data->user->foto)}}" alt="">
                        </div>
                        <div class="ticket-detail">
                            <div class="ticket-title">
                                <h4>{{$data->user->name}}</h4>
                            </div>
                            <div class="ticket-info">
                                <div class="bullet"></div>
                                <div class="text-primary font-weight-600">
                                    {{$data->created_at->format('Y M d H:i:s')}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="ticket-description">
                        <p>{{$data->review}}</p>
                        <div class="gallery">
                            <div class="font-weight-600 mb-2">{{$data->produk->nama}}</div>
                            @foreach ($data->produk->gambarproduk as $produk)
                            <div class="gallery-item" data-image="{{ '/storage/image/'. $produk->gambar }}"
                                data-title="{{ $produk->nama }}">
                            </div>
                            @endforeach

                        </div>
                        <div class="ticket-divider"></div>

                        <div class="ticket-form">
                            <form action="{{route('add-reply', $data->id)}}" method="post" enctype="multipart/form-data">
                                @csrf
                                @if ($data->reply)
                                <div class="form-group">
                                    <label for="">Balasan Anda Sebelumnya</label>
                                    <textarea class="form-control" readonly>{{strip_tags($data->reply->reply)}}</textarea>
                                </div>
                                <div class="ticket-divider"></div>
                                <div class="form-group">
                                    <label for="">Update Balasan Anda</label>
                                    <textarea class="summernote-simple" placeholder="Tulis balasan ..." required name="reply"></textarea>
                                </div>
                                @else
                                <div class="form-group">
                                    <label for="">Balasan Anda</label>
                                    <textarea class="summernote-simple" placeholder="Tulis balasan ..." required name="reply"></textarea>
                                </div>
                                @endif
                                <div class="form-group text-right">
                                    <button class="btn btn-primary btn-lg" type="submit">
                                        Reply
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
