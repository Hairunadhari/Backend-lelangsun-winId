@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Setting {{$data->title}}</h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                aria-controls="home" aria-selected="true">Metadata</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                aria-controls="profile" aria-selected="false">Kontak</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                                aria-controls="contact" aria-selected="false">Lelang</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <form action="{{route('update-setting-metadata',$data->id)}}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <label>Title <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" name="title" value="{{$data->title}}">
                                </div>
                                <div class="form-group">
                                    <label>List Keyword <span style="color: red">*</span></label>
                                    <br>
                                    @foreach ($data->keyword as $item)
                                        
                                        <span class="badge bg-primary text-white" ">{{$item->key}} 
                                            <button type="button" id="delete-key" class="btn btn-danger btn-sm " data-keyid="{{$item->id}}">x</button>
                                        </span>
                                    @endforeach
                                </div>
                                
                                <div class="form-group">
                                    <label>Keyword <span style="color: red">*</span></label>
                                        <input type="text" class="form-control" name="key" required>
                                </div>
                                <div class="form-group">
                                    <label>Deskripsi <span style="color: red">*</span></label>
                                        <textarea class="summernote-simple" placeholder="keterangan..."
                                        name="deskripsi">{{$data->deskripsi}}</textarea>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button class="me-auto btn btn-success mt-3" type="submit">Simpan</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <form action="{{route('update-setting-kontak',$data->id)}}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label>Nomer Telepon <span style="color: red">*</span></label>
                                        <input type="number" class="form-control" name="no_telp" value="{{$data->no_telp}}">
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Nomer Wa <span style="color: red">*</span></label>
                                        <input type="number" class="form-control" name="wa" value="{{$data->no_telp}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Email <span style="color: red">*</span></label>
                                    <input type="email" class="form-control" name="email" value="{{$data->email}}">
                                </div>
                                <div class="form-group">
                                    <label>Alamat <span style="color: red">*</span></label>
                                    <textarea class="form-control" name="alamat">{{$data->alamat}}</textarea>
                                </div>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label>Instagram <span style="color: red">*</span></label>
                                        <input type="text" class="form-control" name="ig" value="{{$data->ig}}">
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Facebook <span style="color: red">*</span></label>
                                        <input type="text" class="form-control" name="fb" value="{{$data->fb}}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label>Twitter <span style="color: red">*</span></label>
                                        <input type="text" class="form-control" name="twitter"
                                            value="{{$data->twitter}}">
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Youtube <span style="color: red">*</span></label>
                                        <input type="text" class="form-control" name="yt" value="{{$data->yt}}">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button class="me-auto btn btn-success mt-3" type="submit">Simpan</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <form action="{{route('update-setting-lelang',$data->id)}}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <label>Waktu Lelang (Detik)</label>
                                    <input type="number" class="form-control" name="waktu_bid" value="{{$data->waktu_bid}}">
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button class="me-auto btn btn-success mt-3" type="submit">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function (){
        $(document).on('click', '#delete-key', function (e) {
            e.preventDefault();
            let keyId = $(this).data('keyid');
            let elementToRemove = $(this).closest('.badge'); // Temukan elemen badge yang akan dihapus

            console.log(keyId);
            $.ajax({
                method: 'post',
                url: '/delete-keyword/' + keyId,
                data: {
                    _method: 'PUT',
                },
                success: function (res) {
                    console.log(res);
                    elementToRemove.remove();
                }
            });
        });
    });
</script>
@endsection
