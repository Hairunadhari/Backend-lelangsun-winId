@extends('app.layouts')
@section('content')
<style>
    .image-container {
    position: relative;
    display: inline-block;
}

.btn-delete {
    position: absolute;
    top: 1px;
    right: 1px;
    background-color: rgba(0,0,0,.5); /* Ganti warna sesuai kebutuhan */
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
}

</style>
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Banner Web</h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="slide-tab" data-toggle="tab" href="#slide" role="tab"
                                aria-controls="slide" aria-selected="true">Slider</a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link " id="beranda-tab" data-toggle="tab" href="#beranda" role="tab"
                                aria-controls="beranda" aria-selected="true">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " id="lelang-tab" data-toggle="tab" href="#lelang" role="tab"
                                aria-controls="lelang" aria-selected="true">Lelang</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " id="events-tab" data-toggle="tab" href="#events" role="tab"
                                aria-controls="events" aria-selected="true">Events</a>
                        </li> --}}
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="slide" role="tabpanel" aria-labelledby="slide-tab">
                            <form action="{{route('superadmin.update-banner-web',$data->id)}}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <label>Judul <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" name="judul" value="{{$data->judul}}">
                                </div>
                                <div class="form-group">
                                    <label>Deskripsi <span style="color: red">*</span></label>
                                        <textarea class="summernote-simple" placeholder="keterangan..."
                                        name="deskripsi">{{$data->deskripsi}}</textarea>
                                </div>
                                <div class="form-group" >
                                    <label for="">Banner Web:</label>
                                    <br>

                                    @foreach ($data->banner_lelang_image as $item)
                                    <div class="image-container">
                                            <img class="ms-auto gambar-lelang" src="{{ asset('storage/image/'.$item->gambar) }}" style="width:150px; height:150px; box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px; margin-left: 10px; margin-bottom:10px;">
                                            <button class="btn-delete" id="deletegambar" data-image-id="{{ $item->id }}">X</button>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="form-group">
                                    <label>Banner Web <small>(disarankan : width:1000px height:900px) </small><span
                                            style="color: red">*</span></label>
                                                <div class="input-images"></div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button class="me-auto btn btn-success mt-3" type="submit">Simpan</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade show active" id="beranda" role="tabpanel" aria-labelledby="beranda-tab">
                            <form action="{{route('superadmin.update-banner-web',$data->id)}}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="form-group" >
                                    <label for="">Banner Events:</label>
                                    <br>
                                    <div class="image-container">
                                        @if ($bannerberanda != null)
                                        <img class="ms-auto gambar-lelang" src="{{ asset('storage/image/'.$bannerberanda->image) }}" style="width:150px; height:150px; box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px; margin-left: 10px; margin-bottom:10px;">
                                        <button class="btn-delete" id="deletegambar" data-image-id="{{ $bannerberanda->id }}">X</button>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Banner Web <small>(disarankan : width:1000px height:900px) </small><span
                                            style="color: red">*</span></label>
                                                <div class="input-images"></div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button class="me-auto btn btn-success mt-3" type="submit">Simpan</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade show active" id="lelang" role="tabpanel" aria-labelledby="lelang-tab">
                            <form action="{{route('superadmin.update-banner-web',$data->id)}}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="form-group" >
                                    <label for="">Banner Web:</label>
                                    <br>
                                    @if ($data->bannerpagelelang != null)
                                    <div class="image-container">
                                            <img class="ms-auto gambar-lelang" src="{{ asset('storage/image/'.$item->gambar) }}" style="width:150px; height:150px; box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px; margin-left: 10px; margin-bottom:10px;">
                                            <button class="btn-delete" id="deletegambar" data-image-id="{{ $item->id }}">X</button>
                                    </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Banner Web <small>(disarankan : width:1000px height:900px) </small><span
                                            style="color: red">*</span></label>
                                                <div class="input-images"></div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button class="me-auto btn btn-success mt-3" type="submit">Simpan</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade show active" id="events" role="tabpanel" aria-labelledby="events-tab">
                            <form action="{{route('superadmin.update-banner-web',$data->id)}}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <label>Judul <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" name="judul" value="{{$data->judul}}">
                                </div>
                                <div class="form-group">
                                    <label>Deskripsi <span style="color: red">*</span></label>
                                        <textarea class="summernote-simple" placeholder="keterangan..."
                                        name="deskripsi">{{$data->deskripsi}}</textarea>
                                </div>
                                <div class="form-group" >
                                    <label for="">Banner Web:</label>
                                    <br>

                                    @foreach ($data->banner_lelang_image as $item)
                                    <div class="image-container">
                                            <img class="ms-auto gambar-lelang" src="{{ asset('storage/image/'.$item->gambar) }}" style="width:150px; height:150px; box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px; margin-left: 10px; margin-bottom:10px;">
                                            <button class="btn-delete" id="deletegambar" data-image-id="{{ $item->id }}">X</button>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="form-group">
                                    <label>Banner Web <small>(disarankan : width:1000px height:900px) </small><span
                                            style="color: red">*</span></label>
                                                <div class="input-images"></div>
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
    $('.input-images').imageUploader({
        imagesInputName: 'gambar',
        maxSize: 2 * 1024 * 1024,

    });
    $(document).on('click', '#deletegambar', function (e) {
        e.preventDefault();
        let id = $(this).data('image-id');
        let elementToRemove = $(this).closest('.image-container'); 
        console.log(id);
        $.ajax({
            method: 'post',
            url: '/superadmin/delete-banner-lelang/' + id,
            data: {
                _method: 'put',
            },
            success: function (res) {
                console.log(res);
                elementToRemove.remove();
            }
        });
    });
</script>
@endsection
