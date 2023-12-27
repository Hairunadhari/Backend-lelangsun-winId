@extends('app.layouts')
@section('content')
<style>
    /* Warna hijau saat "ADA" dipilih */
    .selectgroup-item input[value="ada"]:checked~.selectgroup-button {
        background-color: #63ED7A;
    }

    /* Warna merah saat "TIDAK ADA" dipilih */
    .selectgroup-item input[value="tidak ada"]:checked~.selectgroup-button {
        background-color: #FC5448;
    }
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
    <div class="container-fluid">
        <form action="{{route('superadmin.update-barang-lelang', $data->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="card">
                <div class="card-header">
                    <h4>Edit Barang Lelang</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Pilih Kategori Barang Lelang</label>
                        <select class="form-control selectric" name="kategoribarang_id" id="id_kategoribarangs" onchange="toggleDiv(this.value)">
                            <option value="" disabled>Pilih Barang</option>
                            @foreach ($kategori as $item)
                            <option value="{{ $item->id }}" data-kategori="{{$item->kategori}}"
                                {{ $item->id == $data->kategoribarang->id ? 'selected' : '' }}>
                                {{ $item->kategori }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" class="form-control" name="barang" value="{{ $data->barang }}">
                    </div>
                    <div class="form-group">
                        <label>Brand</label>
                        <input type="text" class="form-control" name="brand" value="{{ $data->brand}}" >
                    </div>
                    <div class="form-group">
                        <label>Warna</label>
                        <input type="text" class="form-control" name="warna" value="{{ $data->warna}}">
                    </div>
                    <div class="form-group">
                        <label>Lokasi Barang</label>
                        <input type="text" class="form-control" name="lokasi_barang" value="{{ $data->lokasi_barang}}">
                    </div>
                    <div id="editinpKendaraan" style="display:none;">
                        <hr>
                        <h5>Spesifikasi Kendaraan</h5>
                        <div class="row">
                            <div class="form-group col-6">
                                <label>Nomer Rangka</label>
                                <input type="text" class="form-control" name="nomer_rangka" value="{{$data->nomer_rangka}}">
                            </div>
                            <div class="form-group col-6">
                                <label>Nomer Mesin</label>
                                <input type="text" class="form-control" name="nomer_mesin" value="{{$data->nomer_mesin}}">
                            </div>
                            <div class="form-group col-6">
                                <label>Tipe Mobil</label>
                                <input type="text" class="form-control" name="tipe_mobil" value="{{$data->tipe_mobil}}">
                            </div>
                            <div class="form-group col-6">
                                <label>Transmisi Mobil</label>
                                <select class="form-control selectric" name="transisi_mobil" >
                                    <option value="Automatic Transmission">Automatic Transmission</option>
                                    <option value="Manual Transmission">Manual Transmission</option>
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label>Bahan Bakar</label>
                                <select class="form-control selectric" name="bahan_bakar" >
                                    <option value="Bensin">Bensin</option>
                                    <option value="Solar">Solar</option>
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label>Odometer</label>
                                <input type="number" class="form-control" name="odometer" value="{{$data->odometer}}">
                            </div>
                        </div>
                        <hr>
                        <h5>Dokumen Kendaraan</h5>
                        <div class="row">
                            <div class="form-group col-6">
                                <label>Grade Utama</label>
                                <select class="form-control selectric" name="grade_utama">
                                    <option value="A" <?php echo ($data->grade_utama === 'A') ? 'selected' : ''; ?>>A</option>
                                    <option value="B" <?php echo ($data->grade_utama === 'B') ? 'selected' : ''; ?>>B</option>
                                    <option value="C" <?php echo ($data->grade_utama === 'C') ? 'selected' : ''; ?>>C</option>
                                    <option value="D" <?php echo ($data->grade_utama === 'D') ? 'selected' : ''; ?>>D</option>
                                    <option value="E" <?php echo ($data->grade_utama === 'E') ? 'selected' : ''; ?>>E</option>
                                    <option value="F" <?php echo ($data->grade_utama === 'F') ? 'selected' : ''; ?>>F</option>
                                </select>
                            </div>                            
                            <div class="form-group col-6">
                                <label>Grade Mesin</label>
                                <select class="form-control selectric" name="grade_mesin" >
                                    <option value="A" <?php echo ($data->grade_utama === 'A') ? 'selected' : ''; ?>>A</option>
                                    <option value="B" <?php echo ($data->grade_utama === 'B') ? 'selected' : ''; ?>>B</option>
                                    <option value="C" <?php echo ($data->grade_utama === 'C') ? 'selected' : ''; ?>>C</option>
                                    <option value="D" <?php echo ($data->grade_utama === 'D') ? 'selected' : ''; ?>>D</option>
                                    <option value="E" <?php echo ($data->grade_utama === 'E') ? 'selected' : ''; ?>>E</option>
                                    <option value="F" <?php echo ($data->grade_utama === 'F') ? 'selected' : ''; ?>>F</option>
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label>Grade Interior</label>
                                <select class="form-control selectric" name="grade_interior" >
                                    <option value="A" <?php echo ($data->grade_utama === 'A') ? 'selected' : ''; ?>>A</option>
                                    <option value="B" <?php echo ($data->grade_utama === 'B') ? 'selected' : ''; ?>>B</option>
                                    <option value="C" <?php echo ($data->grade_utama === 'C') ? 'selected' : ''; ?>>C</option>
                                    <option value="D" <?php echo ($data->grade_utama === 'D') ? 'selected' : ''; ?>>D</option>
                                    <option value="E" <?php echo ($data->grade_utama === 'E') ? 'selected' : ''; ?>>E</option>
                                    <option value="F" <?php echo ($data->grade_utama === 'F') ? 'selected' : ''; ?>>F</option>
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label>Grade Exterior</label>
                                <select class="form-control selectric" name="grade_exterior" >
                                    <option value="A" <?php echo ($data->grade_utama === 'A') ? 'selected' : ''; ?>>A</option>
                                    <option value="B" <?php echo ($data->grade_utama === 'B') ? 'selected' : ''; ?>>B</option>
                                    <option value="C" <?php echo ($data->grade_utama === 'C') ? 'selected' : ''; ?>>C</option>
                                    <option value="D" <?php echo ($data->grade_utama === 'D') ? 'selected' : ''; ?>>D</option>
                                    <option value="E" <?php echo ($data->grade_utama === 'E') ? 'selected' : ''; ?>>E</option>
                                    <option value="F" <?php echo ($data->grade_utama === 'F') ? 'selected' : ''; ?>>F</option>
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label>No Polisi</label>
                                <input type="text" class="form-control" name="no_polisi" value="{{$data->no_polisi}}">
                            </div>
                            <div class="form-group col-6">
                                <label>STNK</label>
                                <div class="selectgroup w-100" id="adaTidakada">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="stnk" value="ada" class="selectgroup-input" <?php echo ($data->stnk === 'ada') ? 'checked' : ''; ?>>
                                        <span class="selectgroup-button">ADA</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="stnk" value="tidak ada" class="selectgroup-input" <?php echo !isset($data->stnk) ? 'checked' : (($data->stnk === 'tidak ada') ? 'checked' : ''); ?>>
                                        <span class="selectgroup-button">TIDAK ADA</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label>STNK Berlaku</label>
                                <input type="date" class="form-control" name="stnk_berlaku" value="{{$data->stnk_berlaku}}">
                            </div>
                            <div class="form-group col-6">
                                <label>Tahun Produksi</label>
                                <select class="form-control selectric" name="tahun_produksi">
                                    <?php for($i = 1900; $i <= date('Y') + 10; $i++) { ?>
                                        <option value="<?= $i ?>" <?php if($i == $data->tahun_produksi) echo 'selected'; ?>><?= $i; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label>BPKB</label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="bpkb" value="ada" class="selectgroup-input" <?php echo ($data->bpkb === 'ada') ? 'checked' : ''; ?>>
                                        <span class="selectgroup-button">ADA</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="bpkb" value="tidak ada" class="selectgroup-input" <?php echo !isset($data->bpkb) ? 'checked' : (($data->bpkb === 'tidak ada') ? 'checked' : ''); ?>>
                                        <span class="selectgroup-button">TIDAK ADA</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label>FAKTUR</label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="faktur" value="ada" class="selectgroup-input" <?php echo ($data->faktur === 'ada') ? 'checked' : ''; ?>>
                                        <span class="selectgroup-button">ADA</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="faktur" value="tidak ada" class="selectgroup-input" <?php echo !isset($data->faktur) ? 'checked' : (($data->faktur === 'tidak ada') ? 'checked' : ''); ?>>
                                        <span class="selectgroup-button">TIDAK ADA</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label>SPH</label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="sph" value="ada" class="selectgroup-input" <?php echo ($data->sph === 'ada') ? 'checked' : ''; ?>>
                                        <span class="selectgroup-button">ADA</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="sph" value="tidak ada" class="selectgroup-input" <?php echo !isset($data->sph) ? 'checked' : (($data->sph === 'tidak ada') ? 'checked' : ''); ?>>
                                        <span class="selectgroup-button">TIDAK ADA</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label>KIR</label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="kir" value="ada" class="selectgroup-input" <?php echo ($data->kir === 'ada') ? 'checked' : ''; ?>>
                                        <span class="selectgroup-button">ADA</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="kir" value="tidak ada" class="selectgroup-input" <?php echo !isset($data->kir) ? 'checked' : (($data->kir === 'tidak ada') ? 'checked' : ''); ?>>
                                        <span class="selectgroup-button">TIDAK ADA</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label>KTP Pemilik</label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="ktp" value="ada" class="selectgroup-input" <?php echo ($data->ktp === 'ada') ? 'checked' : ''; ?>>
                                        <span class="selectgroup-button">ADA</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="ktp" value="tidak ada" class="selectgroup-input" <?php echo !isset($data->ktp) ? 'checked' : (($data->ktp === 'tidak ada') ? 'checked' : ''); ?>>
                                        <span class="selectgroup-button">TIDAK ADA</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label>Kwitansi</label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="kwitansi" value="ada" class="selectgroup-input" <?php echo ($data->kwitansi === 'ada') ? 'checked' : ''; ?>>
                                        <span class="selectgroup-button">ADA</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="kwitansi" value="tidak ada" class="selectgroup-input" <?php echo !isset($data->kwitansi) ? 'checked' : (($data->kwitansi === 'tidak ada') ? 'checked' : ''); ?>>
                                        <span class="selectgroup-button">TIDAK ADA</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="form-group">
                        <label>deskripsi</label>
                        <textarea class="summernote-simple" placeholder="keterangan..." 
                            name="keterangan">{{$data->keterangan}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Gambar:</label>
                        <br>
                        @foreach ($data->gambarlelang as $item)
                        <div class="image-container">
                                <img class="ms-auto gambar-lelang" src="{{ asset('storage/image/'.$item->gambar) }}" style="width:150px; height:150px; box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px; margin-left: 10px; margin-bottom:10px;">
                                <button class="btn-delete" id="deletegambar" data-image-id="{{ $item->id }}">X</button>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="form-group">
                        <label>Gambar</label>
                        <div class="input-images"></div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary mr-1" type="submit">Simpan</button>
                </div>
            </div>
        </form>
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
            url: '/superadmin/delete-gambar-lelang/' + id,
            data: {
                _method: 'put',
            },
            success: function (res) {
                console.log(res);
                elementToRemove.remove();
            }
        });
    });
    window.onload = function () {
        const selectElement = document.getElementById("id_kategoribarang");
        const inpKendaraan = document.getElementById("editinpKendaraan");
        const selectedOption = document.getElementById("id_kategoribarangs").options[document.getElementById("id_kategoribarangs").selectedIndex];
        console.log(selectedOption.value);
    const inputsInpKendaraan = inpKendaraan.querySelectorAll("input[required]");

        const dataKategori = selectedOption.getAttribute("data-kategori");
        if (selectedOption.value == 1 || selectedOption.value == 2) {
        console.log('tess', selectedOption.value);
        inpKendaraan.style.display = "block";

        // Tambahkan atribut "required" kembali pada elemen input
        inputsInpKendaraan.forEach(input => {
            input.setAttribute("required", "required");
        });
    } else {
        inpKendaraan.style.display = "none";

        // Hapus atribut "required" dari elemen input
        inputsInpKendaraan.forEach(input => {
            input.removeAttribute("required");
        });
    }
    };

    function toggleDiv(value) {
    const inpKendaraan = document.getElementById("editinpKendaraan");
    const inputsInpKendaraan = inpKendaraan.querySelectorAll("input[required]");
    const selectedOption = document.getElementById("id_kategoribarangs").options[document.getElementById("id_kategoribarangs").selectedIndex];
    const dataKategori = selectedOption.getAttribute("data-kategori");

    if (value == 1 || value == 2) {
        inpKendaraan.style.display = "block";

        // Tambahkan atribut "required" kembali pada elemen input
        inputsInpKendaraan.forEach(input => {
            input.setAttribute("required", "required");
        });
    } else {
        inpKendaraan.style.display = "none";

        // Hapus atribut "required" dari elemen input
        inputsInpKendaraan.forEach(input => {
            input.removeAttribute("required");
        });
    }
}

</script>
@endsection
