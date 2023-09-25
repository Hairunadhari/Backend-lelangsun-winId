@extends('app.layouts')
@section('content')
<style>
    .reviews img {
        margin-bottom: 20px;
        margin-left: 20px;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
    }
</style>
    <div class="card">
        <div class="card-header">
            <h4>Edit Event</h4>
        </div>
        <form action="{{route('verify-refund', $refund->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" readonly class="form-control" name="penyelenggara" value="{{$refund->npl->peserta_npl->nama}}" >
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" readonly class="form-control" name="judul" value="{{$refund->npl->peserta_npl->email}}">
                    </div>
                    <div class="form-group">
                        <label>Kode NPL</label>
                        <input type="text" readonly class="form-control" name="judul" value="{{$refund->npl->no_npl}}">
                    </div>
                    <div class="form-group">
                        <label>Nominal</label>
                        <input type="text" readonly class="form-control" name="judul" value="{{number_format($refund->npl->harga_item)}}">
                    </div>
                <div class="form-group">
                    <label>Bukti Transfer <small>(png, jpg, jpeg)</small></label>
                    <input type="file" class="form-control" name="bukti" required  id="gambar">
                <div id="preview" class="mt-3"></div>
                </div>
                
            </div>
            <div class="card-footer text-right">
                <button class="btn btn-primary mr-1" type="submit">Submit</button>
            </div>
        </form>
    </div>
<script>
    
    function previewImages() {
        var preview = document.querySelector('#preview');

        // Hapus semua elemen child di dalam elemen #preview
        while (preview.firstChild) {
            preview.removeChild(preview.firstChild);
        }

        if (this.files) {
            [].forEach.call(this.files, readAndPreview);
        }

        function readAndPreview(file) {
            if (!/\.(jpe?g|png)$/i.test(file.name)) {
                alert(file.name + " format tidak sesuai");
                document.querySelector('#gambar').value = '';
                return;
            }
            var reader = new FileReader();
            reader.addEventListener("load", function () {
                var image = new Image();
                image.width = 200;
                image.title = file.name;
                image.src = this.result;
                preview.appendChild(image);
            }, false);
            reader.readAsDataURL(file);
        }
    }
    document.querySelector('#gambar').addEventListener("change", previewImages);
    

    

</script>
@endsection
