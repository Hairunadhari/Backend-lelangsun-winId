@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card p-3">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Detail Barang Lelang</h4>
                            </div>
                            <div class="card-body">
                                This is some text within a card body.
                            </div>
                            <div class="card-footer">
                                Footer Card
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="card chat-box" id="mychatbox">
                            <div class="card-header">
                                <h4>Sistem Lelang</h4>
                            </div>
                            <div class="card-body chat-content">
                            </div>
                            <div class="card-footer chat-form">
                                <form id="chat-form">
                                    <input type="text" class="form-control" placeholder="Type a message">
                                    <button class="btn btn-primary">
                                        <i class="far fa-paper-plane"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
