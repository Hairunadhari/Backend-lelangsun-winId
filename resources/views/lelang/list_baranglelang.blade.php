@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="w-100">Daftar Barang</h4>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#baranglelangmodal">
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
                        <table class="table table-striped" id="kategori-lelang">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>testing</th>
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
@endsection
<!-- Modal -->
<div class="modal fade" id="baranglelangmodal" tabindex="-1" role="dialog" aria-labelledby="baranglelangmodalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="baranglelangmodalLabel">Form Input Barang Lelang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('add-barang-lelang')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Kategori Barang Lelang</label>
                        <select class="form-control" name="kategoribarang_id" required>
                            @foreach ($kategori as $item)
                            <option value="{{ $item->id }}">{{ $item->kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" class="form-control" name="barang" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Pemilik Barang</label>
                        <input type="text" class="form-control" name="nama_pemilik" required>
                    </div>
                    <div class="form-group">
                        <label>Brand</label>
                        <input type="text" class="form-control" name="brand" required>
                    </div>
                    <div class="form-group">
                        <label>Warna</label>
                        <input type="text" class="form-control" name="warna" required>
                    </div>
                    <div class="form-group">
                        <label>Lokasi Barang</label>
                        <input type="text" class="form-control" name="lokasi_barang" required>
                    </div>
                    <hr>
                    <h5>Spesifikasi Kendaraan</h5>
                    <div class="form-group">
                        <label>Nomer Rangka</label>
                        <input type="text" class="form-control" name="nomer_rangka" required>
                    </div>
                    <div class="form-group">
                        <label>Nomer Mesin</label>
                        <input type="text" class="form-control" name="nomer_rangka" required>
                    </div>
                    <div class="form-group">
                        <label>Tipe Mobil</label>
                        <input type="text" class="form-control" name="nomer_rangka" required>
                    </div>
                    <div class="form-group">
                        <label>Transmisi Mobil</label>
                        <input type="text" class="form-control" name="nomer_rangka" required>
                    </div>
                    <div class="form-group">
                        <label>Bahan Bakar</label>
                        <input type="text" class="form-control" name="nomer_rangka" required>
                    </div>
                    <div class="form-group">
                        <label>Odometer</label>
                        <input type="text" class="form-control" name="nomer_rangka" required>
                    </div>
                    <hr>
                    <h5>Dokumen Kendaraan</h5>
                    <div class="form-group">
                        <label>Grade Utama</label>
                        <input type="text" class="form-control" name="nomer_rangka" required>
                    </div>
                    <div class="form-group">
                        <label>Grade Mesin</label>
                        <input type="text" class="form-control" name="nomer_rangka" required>
                    </div>
                    <div class="form-group">
                        <label>Grade Interior</label>
                        <input type="text" class="form-control" name="nomer_rangka" required>
                    </div>
                    <div class="form-group">
                        <label>Grade Exterior</label>
                        <input type="text" class="form-control" name="nomer_rangka" required>
                    </div>
                    <div class="form-group">
                        <label>No Polisi</label>
                        <input type="text" class="form-control" name="nomer_rangka" required>
                    </div>
                    <div class="form-group">
                        <label class="d-block">STNK</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1"
                                checked="">
                            <label class="form-check-label" for="exampleRadios1">
                                Ada
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2"
                                checked="">
                            <label class="form-check-label" for="exampleRadios2">
                                Tidak Ada
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>STNK Berlaku</label>
                        <input type="text" class="form-control" name="nomer_rangka" required>
                    </div>
                    <div class="form-group">
                        <label>Tahun Produksi</label>
                        <input type="text" class="form-control" name="nomer_rangka" required>
                    </div>
                    <div class="form-group">
                        <label class="d-block">BPKB</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1"
                                checked="">
                            <label class="form-check-label" for="exampleRadios1">
                                Ada
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2"
                                checked="">
                            <label class="form-check-label" for="exampleRadios2">
                                Tidak Ada
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="d-block">Faktur</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1"
                                checked="">
                            <label class="form-check-label" for="exampleRadios1">
                                Ada
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2"
                                checked="">
                            <label class="form-check-label" for="exampleRadios2">
                                Tidak Ada
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="d-block">SPH</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1"
                                checked="">
                            <label class="form-check-label" for="exampleRadios1">
                                Ada
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2"
                                checked="">
                            <label class="form-check-label" for="exampleRadios2">
                                Tidak Ada
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="d-block">KIR</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1"
                                checked="">
                            <label class="form-check-label" for="exampleRadios1">
                                Ada
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2"
                                checked="">
                            <label class="form-check-label" for="exampleRadios2">
                                Tidak Ada
                            </label>
                        </div>
                    </div>
                    <hr>
                    <h5>media</h5>
                    <div class="form-group">
                        <label>gambar barang</label>
                        <input type="text" class="form-control" name="nomer_rangka" required>
                    </div>
                    <div class="form-group">
                        <label>deskripsi</label>
                        <input type="text" class="form-control" name="nomer_rangka" required>
                    </div>
                    <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="value" value="developer" class="selectgroup-input">
                          <span class="selectgroup-button">Developer</span>
                        </label>
                        <label class="selectgroup-item">
                          <input type="radio" name="value" value="ceo" class="selectgroup-input">
                          <span class="selectgroup-button">CEO</span>
                        </label>
                      </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-warning" id="resetButton">Reset</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function previewImages() {
        var preview = document.querySelector('#preview');
        if (this.files) {
            [].forEach.call(this.files, readAndPreview);
        }

        function readAndPreview(file) {
            if (!/\.(jpe?g|png|gif|webp)$/i.test(file.name)) {
                return alert(file.name + " is not an image");
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

    document.querySelector('#resetButton').addEventListener('click', function () {
        document.querySelector('#preview').innerHTML = '';
    });

    function formatNumber(input) {
        // Menghilangkan karakter selain angka
        var num = input.value.replace(/[^0-9]/g, '');

        // Memformat angka menjadi format ribuan dan desimal
        var formattedNum = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(num);

        // Memasukkan nilai format ke dalam input
        input.value = formattedNum;
    }

 
    function validateform(){
        validate=true;
        var validate_form=document.querySelectorAll(".main.active input");
        validate_form.forEach(function(val){
            val.classList.remove('warning');
            if(val.hasAttribute('require')){
                if(val.value.length==0){
                    validate=false;
                    val.classList.add('warning');
                }
            }
        });
        return validate;
}

</script>
