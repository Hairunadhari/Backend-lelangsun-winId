@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="w-100">
                        <h4>Daftar NPL Peserta</h4>
                    </div>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#pembeliannplmodal">
                        <span class="text">+ Tambah NPL Peserta</span>
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
                    <ul class="nav nav-pills" id="myTab3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab3" data-toggle="tab" href="#home3" role="tab"
                                aria-controls="home" aria-selected="true">Peserta Aktif</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab3" data-toggle="tab" href="#profile3" role="tab"
                                aria-controls="profile" aria-selected="false">Peserta Tidak Aktif</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent2">
                        <div class="tab-pane fade show active" id="home3" role="tabpanel" aria-labelledby="home-tab3">
                            <table class="table table-striped w-100" id="activenpl">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                         <th>No Telp</th>
                                        <th>Npl</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="profile3" role="tabpanel" aria-labelledby="profile-tab3">
                            <table class="table table-striped w-100" id="not-active">
                                <thead>
                                    <tr>
                                         <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>No Telp</th>
                                        <th>Npl</th>
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
        $('#activenpl').DataTable({
            processing: true,
            ordering: false,
            searching: true,
            serverSide: true,
            ajax: {
                url: '{{ url()->current() }}',
                data: function (data) {
                    data.status = 'active';
                }
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "peserta_npl.nama"
                },
                {
                    data: "peserta_npl.email"
                },
                {
                    data: "peserta_npl.no_hp"
                },
                {
                    data: "no_npl"
                },
                {
                    data: null,
                    render: function (data) {
                        var detailUrl = '/detail-npl/' + data.id;
                        return `
                            <span><a class="btn btn-primary" href="${detailUrl}"><i class="fas fa-info"></i></a></span>
                        `;
                    }
                }
            ],
        });
    });

</script>
@endsection

@section('modal')
<style>
    .form-group img {
        padding: 0rem;
        border: 1px solid #dee2e6;
    }

</style>
<!-- Modal -->
<div class="modal fade" id="pembeliannplmodal" tabindex="-1" role="dialog" aria-labelledby="pembeliannpllabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pembeliannpllabel">Form Input NPL Peserta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('add-npl')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="string" class="form-control" name="peserta_npl_id" value="{{$id}}" required>
                    <input type="string" class="form-control" name="type_pembelian" value="offline" required>
                    <div class="form-group">
                        <label>Event <span style="color: red">*</span></label>
                        <select class="form-control selectric" id="eventlist" name="event_lelang_id">
                            <option selected disabled>-- Pilih Event --</option>
                            @foreach ($event as $item)
                            <option value="{{ $item->id }}">{{ $item->judul }} ({{$item->waktu}}) ( kategori:
                                {{$item->kategori_barang->kategori}} )</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Harga Npl <span style="color: red">*</span></label>
                        <input type="text" class="form-control" id="harganpl" name="harga_npl" required readonly>
                    </div>
                    <div class="form-group">
                        <label>Jumlah NPL yg dibeli <span style="color: red">*</span></label>
                        <input type="number" class="form-control" id="jumlahNpl" name="jumlah_tiket" required>
                    </div>
                    <div class="form-group">
                        <label>Nominal Transfer<span style="color: red">*</span></label>
                        <input type="text" class="form-control" id="nominalTransfer" name="nominal" readonly required>
                    </div>
                    <div class="form-group">
                        <label>No Rekening<span style="color: red">*</span></label>
                        <input type="text" class="form-control" name="no_rek" required>
                    </div>
                    <div class="form-group">
                        <label>Waktu Transfer<span style="color: red">*</span></label>
                        <input type="datetime-local" class="form-control" name="tgl_transfer" required>
                    </div>
                    <div class="form-group">
                        <label>Bukti Transfer <span style="color: red">*</span></label>
                        <input type="file" class="form-control" name="bukti" required id="gambarktp">
                        <div id="previewktp" class="mt-3"></div>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="customRadioInline1" name="type_transaksi" value="cash"
                            class="custom-control-input">
                        <label class="custom-control-label" for="customRadioInline1">Cash dengan admin SUN</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="customRadioInline2" name="type_transaksi" value="transfer"
                            class="custom-control-input">
                        <label class="custom-control-label" for="customRadioInline2">Transfer</label>
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
    $(document).ready(function () {
        $('#eventlist').on('change', function () {
            var eventId = this.value;
            $('#harganpl').html('');
            $.ajax({
                url: "get-harganpl-by-event/" + eventId,
                type: 'get',
                success: function (res) {
                    console.log(res);
                    var formattedNumber = parseFloat(res).toLocaleString(
                        'id-ID'); // Ganti 'id-ID' sesuai dengan kode bahasa yang sesuai
                    $('#harganpl').val(formattedNumber);

                    $('#jumlahNpl').on('input', function () {
                        var jumlahNpl = parseFloat($(this).val());
                        if (!isNaN(jumlahNpl)) {
                            var hargaNpl = parseFloat(res);
                            var totalHarga = jumlahNpl * hargaNpl;
                            $('#nominalTransfer').val(totalHarga.toLocaleString(
                                'id-ID'));
                        } else {
                            // Jika nilai "Jumlah NPL yang dibeli" kosong, kosongkan "Nominal Transfer"
                            $('#nominalTransfer').val('');
                        }
                    });
                }
            });
        });
    });


    function previewgambarktp() {
        var preview = document.querySelector('#previewktp');

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
    document.querySelector('#gambarktp').addEventListener("change", previewgambarktp);

</script>

<!-- /.container-fluid -->
@endsection
