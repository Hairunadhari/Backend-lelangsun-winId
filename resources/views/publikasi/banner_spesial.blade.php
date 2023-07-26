@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="w-100">Daftar Banner Spesial</h4>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#bannerspesial">
                <span class="text">+ Tambah</span>
            </button>
          </div>
          <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible text-center fade show" role="alert">
                <strong>{{ session('success') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
              <table class="table table-striped w-100" id="tablebanner-3">
                <thead>                                 
                  <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Opsi</th>
                  </tr>
                </thead>
                <tbody>                                 
                </tbody>
              </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    $(document).ready(function () {
        $('#tablebanner-3').DataTable({
            processing: true,
            ordering: false,
            searching: false,
            // fixedHeader: true,
        ajax: '{{ url()->current() }}',
        columns: [
          {
             render: function (data, type, row, meta) {
               return meta.row + meta.settings._iDisplayStart + 1;
             },
          },
          { 
            data: "gambar",
          render: function (data) {
            return '<img src="/storage/image/' + data + '"style="width: 150px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin:5px; ">';
          },
            },
            {
                data: null,
          render: function (data) {
            var deleteUrl = '/delete-banner-spesial/' + data.id;
            var editUrl = '/edit-banner-spesial/' + data.id;
            return `
              <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan menghapus data ini ?');">
                <span><a class="btn btn-primary" href="${editUrl}"><i class="far fa-edit"></i>Edit</a></span>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
                <button class="btn btn-danger" type="submit"><i class="far fa-trash-alt"></i> Hapus</button>
              </form>
            `;
          },
        },

         
        ],
      });
    });
  </script>
@endsection
<!-- Modal -->
<div class="modal fade" id="bannerspesial" tabindex="-1" role="dialog" aria-labelledby="bannerspesialLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bannerspesialLabel">Form Input Banner</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('add-banner-spesial')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Gambar</label>
                        <input type="file" class="form-control" name="gambar" required id="gambar">
                    </div>
                    <div id="preview"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
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

<!-- /.container-fluid -->
