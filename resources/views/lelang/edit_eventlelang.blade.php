@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Edit Event</h4>
            </div>
            <form action="{{route('update-event-lelang', $data->id)}}" method="post">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label>Nama Event</label>
                        <input type="text" class="form-control" value="{{ $data->judul }}" name="judul">
                    </div>
                    <div class="form-group">
                        <label>Nama Event</label>
                        <select class="form-control select2" name="kategori_id">
                             @foreach ($kategori as $item)
                            <option value="{{ $item->id }}"
                                {{ $item->id == $data->kategori_id ? 'selected' : '' }}>
                                {{ $item->kategori }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Waktu Event <span style="color: red">*</span></label>
                        <input type="datetime-local" class="form-control" name="waktu" value="{{$data->waktu}}">
                    </div>
                    <div class="form-group">
                        <label>Alamat Event <span style="color: red">*</span></label>
                        <textarea class="form-control" name="alamat" >{{$data->alamat}}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Link Lokasi <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="link_lokasi" value="{{$data->link_lokasi}}">
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Event<span style="color: red">*</span></label>
                        <textarea class="summernote-simple" placeholder="keterangan..." name="deskripsi">{{$data->deskripsi}}</textarea>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary mr-1" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
