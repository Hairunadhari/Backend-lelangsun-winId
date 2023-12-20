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
</style>
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
                    <ul class="nav nav-pills" id="myTab3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab"
                                aria-controls="home" aria-selected="true">Data Aktif</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab"
                                aria-controls="profile" aria-selected="false">Data Tidak Aktif</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent2">
                        <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="home-tab3">
                            <table class="table table-striped w-100" id="barang-lelang">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>Gambar Barang</th>
                                        <th>Kelengkapan Dokumen</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">
                            <table class="table table-striped w-100" id="barang-lelang-notactive">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>Gambar Barang</th>
                                        <th>Kelengkapan Dokumen</th>
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
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#barang-lelang').DataTable({
            processing: true,
            ordering: false,
            serverSide: true,
            ajax: {
                url: '{{ url()->current() }}',
                data: function (data) {
                    data.status = 'active';
                }
            },
            columns: [{
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "barang",
                },
                {
                    data: "kategoribarang.kategori",
                },
                {
                    data: "gambarlelang",
                    render: function (data) {
                        let a = ''; // Deklarasikan variabel di luar kondisi if-else

                        if (data !== null && data.length > 0) { // Periksa apakah data tidak null dan memiliki elemen
                            a = '<img src="/storage/image/' + data[0].gambar + '" style="max-width: 15vw; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin:5px; padding:0.25rem; border:1px solid #dee2e6; ">';
                        }

                        return a;
                    },
                },

                {
                    // This column displays STNK and BPKB data
                    data: null,
                    render: function (data) {
                        if (data.kategoribarang_id == 1 || data.kategoribarang_id == 2) {
                            let output = ''; // Initialize the output variable

                            // Check STNK status and add corresponding information to the output
                            if (data.stnk === 'ada') {
                                output +=
                                    `<div>1. STNK: <span class="badge badge-success mb-1">Ada</span></div>`;
                            } else if (data.stnk === 'tidak ada') {
                                output +=
                                    `<div>1. STNK: <span class="badge badge-danger mb-1">Tidak Ada</span></div>`;
                            }

                            if (data.bpkb === 'ada') {
                                output +=
                                    `<div>2. BPKB: <span class="badge badge-success mb-1">Ada</span></div>`;
                            } else if (data.bpkb === 'tidak ada') {
                                output +=
                                    `<div>2. BPKB: <span class="badge badge-danger mb-1">Tidak Ada</span></div>`;
                            }

                            if (data.faktur === 'ada') {
                                output +=
                                    `<div>3. Faktur: <span class="badge badge-success mb-1">Ada</span></div>`;
                            } else if (data.faktur === 'tidak ada') {
                                output +=
                                    `<div>3. Faktur: <span class="badge badge-danger mb-1">Tidak Ada</span></div>`;
                            }

                            if (data.ktp === 'ada') {
                                output +=
                                    `<div>4. KTP PEMILIK: <span class="badge badge-success mb-1">Ada</span></div>`;
                            } else if (data.ktp === 'tidak ada') {
                                output +=
                                    `<div>4. KTP PEMILIK: <span class="badge badge-danger mb-1">Tidak Ada</span></div>`;
                            }

                            if (data.kwitansi === 'ada') {
                                output +=
                                    `<div>5. KWITANSI: <span class="badge badge-success mb-1">Ada</span></div>`;
                            } else if (data.kwitansi === 'tidak ada') {
                                output +=
                                    `<div>5. KWITANSI: <span class="badge badge-danger mb-1">Tidak Ada</span></div>`;
                            }

                            if (data.sph === 'ada') {
                                output +=
                                    `<div>6. SPH: <span class="badge badge-success mb-1">Ada</span></div>`;
                            } else if (data.sph === 'tidak ada') {
                                output +=
                                    `<div>6. SPH: <span class="badge badge-danger mb-1">Tidak Ada</span></div>`;
                            }
                            if (data.kir === 'ada') {
                                output +=
                                    `<div>7. KIR: <span class="badge badge-success">Ada</span></div>`;
                            } else if (data.kir === 'tidak ada') {
                                output +=
                                    `<div>7. KIR: <span class="badge badge-danger">Tidak Ada</span></div>`;
                            }

                            // Return the final output
                            return output;
                        } else {
                            return '-'; // Display '-' if kategoribarang_id is neither 1 nor 2
                        }
                    }
                },


                {
                    data: null,
                    render: function (data) {
                        var deleteUrl = '/superadmin/delete-barang-lelang/' + data.id;
                        var editUrl = '/superadmin/edit-barang-lelang/' + data.id;
                        return `
                        <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan menghapus data ini ?');">
                        <span><a class="btn btn-primary" href="${editUrl}"><i class="far fa-edit"></i></a></span>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">
                        <button class="btn btn-danger" type="submit"><i class="far fa-trash-alt"></i></button>
                        </form>
                    `;
                    },
                },

            ],
        });
        $('#barang-lelang-notactive').DataTable({
            processing: true,
            ordering: false,
            serverSide: true,
            ajax: {
                url: '{{ url()->current() }}',
                data: function (data) {
                    data.status = 'notactive';
                }
            },
            columns: [{
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "barang",
                },
                {
                    data: "kategoribarang.kategori",
                },
                {
                    data: "gambarlelang",
                    render: function (data) {
                        return '<img src="/storage/image/' + data[0].gambar +
                            '" style="max-width: 15vw; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 2px; margin:5px; padding:0.25rem; border:1px solid #dee2e6; ">';
                    },
                },

                {
                    // This column displays STNK and BPKB data
                    data: null,
                    render: function (data) {
                        if (data.kategoribarang_id == 1 || data.kategoribarang_id == 2) {
                            let output = ''; // Initialize the output variable

                            // Check STNK status and add corresponding information to the output
                            if (data.stnk === 'ada') {
                                output +=
                                    `<div>1. STNK: <span class="badge badge-success mb-1">Ada</span></div>`;
                            } else if (data.stnk === 'tidak ada') {
                                output +=
                                    `<div>1. STNK: <span class="badge badge-danger mb-1">Tidak Ada</span></div>`;
                            }

                            if (data.bpkb === 'ada') {
                                output +=
                                    `<div>2. BPKB: <span class="badge badge-success mb-1">Ada</span></div>`;
                            } else if (data.bpkb === 'tidak ada') {
                                output +=
                                    `<div>2. BPKB: <span class="badge badge-danger mb-1">Tidak Ada</span></div>`;
                            }
                            if (data.faktur === 'ada') {
                                output +=
                                    `<div>3. Faktur: <span class="badge badge-success mb-1">Ada</span></div>`;
                            } else if (data.faktur === 'tidak ada') {
                                output +=
                                    `<div>3. Faktur: <span class="badge badge-danger mb-1">Tidak Ada</span></div>`;
                            }

                            if (data.ktp === 'ada') {
                                output +=
                                    `<div>4. KTP PEMILIK: <span class="badge badge-success mb-1">Ada</span></div>`;
                            } else if (data.ktp === 'tidak ada') {
                                output +=
                                    `<div>4. KTP PEMILIK: <span class="badge badge-danger mb-1">Tidak Ada</span></div>`;
                            }

                            if (data.kwitansi === 'ada') {
                                output +=
                                    `<div>5. KWITANSI: <span class="badge badge-success mb-1">Ada</span></div>`;
                            } else if (data.kwitansi === 'tidak ada') {
                                output +=
                                    `<div>5. KWITANSI: <span class="badge badge-danger mb-1">Tidak Ada</span></div>`;
                            }


                            if (data.sph === 'ada') {
                                output +=
                                    `<div>6. SPH: <span class="badge badge-success mb-1">Ada</span></div>`;
                            } else if (data.sph === 'tidak ada') {
                                output +=
                                    `<div>6. SPH: <span class="badge badge-danger mb-1">Tidak Ada</span></div>`;
                            }
                            if (data.kir === 'ada') {
                                output +=
                                    `<div>7. KIR: <span class="badge badge-success">Ada</span></div>`;
                            } else if (data.kir === 'tidak ada') {
                                output +=
                                    `<div>7. KIR: <span class="badge badge-danger">Tidak Ada</span></div>`;
                            }

                            // Return the final output
                            return output;
                        } else {
                            return '-'; // Display '-' if kategoribarang_id is neither 1 nor 2
                        }
                    }
                },


                {
                    data: null,
                    render: function (data) {
                        var activeurl = '/superadmin/active-barang-lelang/' + data.id;
                        return `
                        <form action="${activeurl}" method="POST" onsubmit="return confirm('Apakah anda yakin akan mengaktifkan data ini ?');">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">
                        <button class="btn btn-success" type="submit"><i class="fas fa-sync-alt"></i></button>
                        </form>
                    `;
                    },
                },

            ],
        });
    });

</script>
@endsection
@section('modal')
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

            <form action="{{route('superadmin.add-barang-lelang')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kategori Barang Lelang <span style="color: red">*</span></label>
                        <select class="form-control selectric" name="kategoribarang_id" id="id_kategoribarang"
                            onchange="toggleDiv(this.value)">
                            <option readonly>-- Pilih Kategori --</option>
                            @foreach ($kategori as $item)
                            <option value="{{ $item->id }}" data-kategori="{{$item->kategori}}">{{ $item->kategori }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Barang <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="barang" required>
                    </div>
                    <div class="form-group">
                        <label>Brand <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="brand" required>
                    </div>
                    <div class="form-group">
                        <label>Warna <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="warna" required>
                    </div>
                    <div class="form-group">
                        <label>Lokasi Barang <span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="lokasi_barang" required>
                    </div>
                    <div id="inpKendaraan" style="display:none;">
                        <hr>
                        <h5>Spesifikasi Kendaraan</h5>
                        <div class="row">
                            <div class="form-group col-6">
                                <label>Nomer Rangka</label>
                                <input type="text" class="form-control" name="nomer_rangka">
                            </div>
                            <div class="form-group col-6">
                                <label>Nomer Mesin</label>
                                <input type="text" class="form-control" name="nomer_mesin">
                            </div>
                            <div class="form-group col-6">
                                <label>Tipe Kendaraan <span style="color: red">*</span></label>
                                <input type="text" class="form-control" name="tipe_mobil" required>
                            </div>
                            <div class="form-group col-6">
                                <label>Transmisi Kendaraan <span style="color: red">*</span></label>
                                <select class="form-control selectric" name="transisi_mobil" required>
                                    <option value="Automatic Transmission">Automatic Transmission</option>
                                    <option value="Manual Transmission">Manual Transmission</option>
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label>Bahan Bakar <span style="color: red">*</span></label>
                                <select class="form-control selectric" name="bahan_bakar" required>
                                    <option value="Bensin">Bensin</option>
                                    <option value="Solar">Solar</option>
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label>Odometer</label>
                                <input type="number" class="form-control" name="odometer">
                            </div>
                        </div>
                        <hr>
                        <h5>Dokumen Kendaraan</h5>
                        <div class="row">
                            <div class="form-group col-6">
                                <label>Grade Utama <span style="color: red">*</span></label>
                                <select class="form-control selectric" name="grade_utama" required>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                    <option value="E">E</option>
                                    <option value="F">F</option>
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label>Grade Mesin <span style="color: red">*</span></label>
                                <select class="form-control selectric" name="grade_mesin" required>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                    <option value="E">E</option>
                                    <option value="F">F</option>
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label>Grade Interior <span style="color: red">*</span></label>
                                <select class="form-control selectric" name="grade_interior" required>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                    <option value="E">E</option>
                                    <option value="F">F</option>
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label>Grade Exterior <span style="color: red">*</span></label>
                                <select class="form-control selectric" name="grade_exterior" required>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                    <option value="E">E</option>
                                    <option value="F">F</option>
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label>No Polisi <span style="color: red">*</span></label>
                                <input type="text" class="form-control" name="no_polisi" required>
                            </div>
                            <div class="form-group col-6">
                                <label>STNK <span style="color: red">*</span></label>
                                <div class="selectgroup w-100" id="adaTidakada">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="stnk" value="ada" class="selectgroup-input">
                                        <span class="selectgroup-button">ADA</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="stnk" value="tidak ada" class="selectgroup-input"
                                            checked>
                                        <span class="selectgroup-button">TIDAK ADA</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label>STNK Berlaku <span style="color: red">*</span></label>
                                <input type="date" class="form-control" name="stnk_berlaku" required>
                            </div>
                            <div class="form-group col-6">
                                <label>Tahun Produksi <span style="color: red">*</span></label>
                                <select class="form-control selectric" name="tahun_produksi">
                                    <?php for($i = 1900; $i <= date('Y') + 10; $i++) { ?>
                                    <option value="<?= $i ?>" <?php if($i == date('Y')) echo 'selected'; ?>><?= $i; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label>BPKB <span style="color: red">*</span></label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="bpkb" value="ada" class="selectgroup-input">
                                        <span class="selectgroup-button">ADA</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="bpkb" value="tidak ada" class="selectgroup-input"
                                            checked>
                                        <span class="selectgroup-button">TIDAK ADA</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label>FAKTUR <span style="color: red">*</span></label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="faktur" value="ada" class="selectgroup-input">
                                        <span class="selectgroup-button">ADA</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="faktur" value="tidak ada" class="selectgroup-input"
                                            checked>
                                        <span class="selectgroup-button">TIDAK ADA</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label>SPH <span style="color: red">*</span></label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="sph" value="ada" class="selectgroup-input">
                                        <span class="selectgroup-button">ADA</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="sph" value="tidak ada" class="selectgroup-input"
                                            checked>
                                        <span class="selectgroup-button">TIDAK ADA</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label>KIR <span style="color: red">*</span></label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="kir" value="ada" class="selectgroup-input">
                                        <span class="selectgroup-button">ADA</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="kir" value="tidak ada" class="selectgroup-input"
                                            checked>
                                        <span class="selectgroup-button">TIDAK ADA</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label>KTP Pemilik <span style="color: red">*</span></label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="ktp" value="ada" class="selectgroup-input">
                                        <span class="selectgroup-button">ADA</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="ktp" value="tidak ada" class="selectgroup-input"
                                            checked>
                                        <span class="selectgroup-button">TIDAK ADA</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label>Kwitansi <span style="color: red">*</span></label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="kwitansi" value="ada" class="selectgroup-input">
                                        <span class="selectgroup-button">ADA</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="kwitansi" value="tidak ada" class="selectgroup-input"
                                            checked>
                                        <span class="selectgroup-button">TIDAK ADA</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="form-group">
                        <label>deskripsi <span style="color: red">*</span></label>
                        <textarea class="summernote-simple" placeholder="keterangan..." required
                            name="keterangan"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Gambar</label>
                        <div class="input-images"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('.input-images').imageUploader({
        imagesInputName: 'gambar',
        maxSize: 2 * 1024 * 1024,

    });
    // Asumsikan kode ini berada di dalam sebuah tag <script> atau file JavaScript
    function toggleDiv(value) {
        const inpKendaraan = document.getElementById("inpKendaraan");
        const inputsInpKendaraan = inpKendaraan.querySelectorAll("input[required]");
        const selectedOption = document.getElementById("id_kategoribarang").options[document.getElementById(
            "id_kategoribarang").selectedIndex];
        const dataKategori = selectedOption.getAttribute("data-kategori");

        if (dataKategori === "Motor" || dataKategori === "Mobil" || dataKategori === "motor" || dataKategori ===
            "mobil") {
            console.log('tess', dataKategori);
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
