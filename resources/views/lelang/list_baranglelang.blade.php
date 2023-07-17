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
                        <select class="form-control selectric" name="kategoribarang_id" required>
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
                    <div class="row">
                        <div class="form-group col-6">
                            <label>Nomer Rangka</label>
                            <input type="text" class="form-control" name="nomer_rangka" required>
                        </div>
                        <div class="form-group col-6">
                            <label>Nomer Mesin</label>
                            <input type="text" class="form-control" name="nomer_rangka" required>
                        </div>
                        <div class="form-group col-6">
                            <label>Tipe Mobil</label>
                            <input type="text" class="form-control" name="nomer_rangka" required>
                        </div>
                        <div class="form-group col-6">
                            <label>Transmisi Mobil</label>
                            <select class="form-control selectric" name="kategoribarang_id" required>
                                <option value="Automatic Transmission">Automatic Transmission</option>
                                <option value="Manual Transmission">Manual Transmission</option>
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label>Bahan Bakar</label>
                            <select class="form-control selectric" name="kategoribarang_id" required>
                                <option value="Bensin">Bensin</option>
                                <option value="Solar">Solar</option>
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label>Odometer</label>
                            <input type="number" class="form-control" name="nomer_rangka" required>
                        </div>
                    </div>
                    <hr>
                    <h5>Dokumen Kendaraan</h5>
                    <div class="row">
                        <div class="form-group col-6">
                            <label>Grade Utama</label>
                            <select class="form-control selectric" name="kategoribarang_id" required>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label>Grade Mesin</label>
                            <select class="form-control selectric" name="kategoribarang_id" required>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label>Grade Interior</label>
                            <select class="form-control selectric" name="kategoribarang_id" required>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label>Grade Exterior</label>
                            <select class="form-control selectric" name="kategoribarang_id" required>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label>No Polisi</label>
                            <input type="text" class="form-control" name="nomer_rangka" required>
                        </div>
                        <div class="form-group col-6">
                            <label class="d-block">STNK</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="stnk" id="stnk-ada"
                                    checked="">
                                <label class="form-check-label" for="stnk-ada">
                                    Ada
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="stnk" id="stnk-tidakada"
                                    checked="">
                                <label class="form-check-label" for="stnk-tidakada">
                                    Tidak Ada
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label>STNK Berlaku</label>
                            <input type="text" class="form-control" name="nomer_rangka" required>
                        </div>
                        <div class="form-group col-6">
                            <label>Tahun Produksi</label>
                            <input type="text" class="form-control" name="nomer_rangka" required>
                        </div>
                        <div class="form-group col-6">
                            <label class="d-block">BPKB</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bpkb" id="bpkb-ada"
                                    checked="">
                                <label class="form-check-label" for="bpkb-ada">
                                    Ada
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="bpkb" id="bpkb-tdkada"
                                    checked="">
                                <label class="form-check-label" for="bpkb-tdkada">
                                    Tidak Ada
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label class="d-block">Faktur</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="faktur" id="faktur-ada"
                                    checked="">
                                <label class="form-check-label" for="faktur-ada">
                                    Ada
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="faktur" id="faktur-tdkada"
                                    checked="">
                                <label class="form-check-label" for="faktur-tdkada">
                                    Tidak Ada
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label class="d-block">SPH</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sph" id="sph-ada"
                                    checked="">
                                <label class="form-check-label" for="sph-ada">
                                    Ada
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sph" id="sph-tdkada"
                                    checked="">
                                <label class="form-check-label" for="sph-tdkada">
                                    Tidak Ada
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label class="d-block">KIR</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="kir" id="kir-ada"
                                    checked="">
                                <label class="form-check-label" for="kir-ada">
                                    Ada
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="kir" id="kir-tdkada"
                                    checked="">
                                <label class="form-check-label" for="kir-tdkada">
                                    Tidak Ada
                                </label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label>gambar barang</label>
                        <input type="text" class="form-control" name="nomer_rangka" required>
                    </div>
                    <div class="form-group">
                        <label>deskripsi</label>
                        <textarea class="summernote-simple"></textarea>
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
