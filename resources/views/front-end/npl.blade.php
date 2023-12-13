 @extends('front-end.layout')
@section('content')
<style>
    .con-kontak {
        background-image: url('asset-lelang/kontak1.png');
        height: 35vh;
        background-position: center;
        background-size: cover;
        width: 100%;
        padding: 150px 50px 50px 50px;
        color: white;
        text-align: center;
    }

    .parent {
        padding: 50px;
        color: white;
        text-align: center;
        display: flex;
        justify-content: center;
    }

    .con-kontak2 {
        background-image: url('asset-lelang/lelang2.jpg');
        height: auto;
        background-position: center;
        background-size: cover;
        width: 100%;
        display: flex;
        justify-content: center;
        padding-top: 100px;
        padding-bottom: 100px;
    }

    .card-kon,
    .card-kon2,
    .card-kon3 {
        background-color: #31869b;
        padding: 40px 40px 10px 40px;
    }

    .card-kon2,
    .card-kon3 {
        margin-left: 40px;
    }

    .a {
        width: 500px;
    }

    .lokasi {
        display: flex;
        justify-content: center;
    }

    .lokasi iframe {
        width: 100%;
        padding: 0px 100px 100px 100px;
    }

    .scroll {
        height: 500px;
        overflow: scroll;
    }

    .heads {
        display: flex;
        justify-content: space-between;
        padding: 0px 10px 10px 10px;
    }

    .button {
        width: 300px;
    }

    .heads a {
        text-decoration: none;
        color: black;
    }

    .scroll {
        height: 500px;
        overflow: scroll;
    }

    @media (max-width: 600px) {
        .parent {
            padding: 20px;
            color: white;
            text-align: center;
            display: block;
        }

        .a {
            width: 200px;
        }

        .card-kon2,
        .card-kon3 {
            margin-top: 10px;
            margin-left: 0px;
        }

        .lokasi iframe {
            width: 100%;
            padding: 20px;
        }
    }

</style>
<section id="kontak">
    <div class="con-kontak">
        <img src="{{ asset('asset-lelang/profile_picture.png') }}" width="150" alt="">
    </div>
    <div class="con-kontak2">
        <div class="card" style="width: 80%;">
            <div class="card-body">
                <div class="heads">
                    <a href="{{route('front-end-notif')}}">Profile</a>
                    <a href="{{route('front-end-npl')}}">NPL</a>
                    <a href="{{route('front-end-pelunasan')}}">Pelunasan Barang Lelang</a>
                    <a href="{{route('front-end-pesan')}}">Notifikasi</a>
                </div>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-success m-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="fas fa-plus"></i> Beli NPL
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Form Beli NPL</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('add-npl-user')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <input type="hidden" class="form-control" name="user_id"
                                            value="{{Auth::user()->id}}">
                                        <input type="hidden" class="form-control" name="type_pembelian" value="online"
                                            required>
                                        <div class="form-group mb-3">
                                            <label>Event <span style="color: red">*</span></label>
                                            <select class="form-control selectric" id="eventlist"
                                                name="event_lelang_id">
                                                <option selected disabled>-- Pilih Event --</option>
                                                @foreach ($event as $item)
                                                <option value="{{ $item->id }}">{{ $item->judul }} ({{$item->waktu}})
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Harga Npl <span style="color: red">*</span></label>
                                            <input type="text" class="form-control" id="harganpl" name="harga_npl"
                                                required readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Jumlah NPL yg dibeli <span style="color: red">*</span></label>
                                            <input type="number" class="form-control" id="jumlahNpl" name="jumlah_tiket"
                                                required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Nominal Transfer<span style="color: red">*</span></label>
                                            <input type="text" class="form-control" id="nominalTransfer" name="nominal"
                                                readonly required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>No Rekening<span style="color: red">*</span></label>
                                            <input type="text" class="form-control" name="no_rek" required>
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <label>Bukti Transfer <span style="color: red">*</span></label>
                                            <input type="file" class="form-control" name="bukti" required accept=".jpg,.png,.jpeg"
                                                id="gambarktp">
                                            <div id="previewktp" class="mt-3"></div>
                                        </div>
                                        <input type="hidden" name="type_transaksi" value="transfer">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="scroll">
                    <table class="table table-bordered">
                        <thead style="position: sticky; top: 0; background-color: rgb(224, 13, 13);">
                            <tr>
                                <th>No</th>
                                <th>Event</th>
                                <th>Kode NPL</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        @php
                        $no = 1;
                        @endphp
                        <tbody>
                            @forelse ($npl as $p)
                            <tr>
                                <td>{{$no++}}</td>
                                <td>{{$p->event_lelang->judul}}</td>
                                <td>{{$p->kode_npl}}</td>
                                <td>
                                    @if ($p->status_npl == 'verifikasi')
                                    <span class=" badge bg-warning">Verifikasi</span>
                                    @elseif($p->status_npl == 'pengajuan')
                                    <span class=" badge bg-primary">Pengajuan</span>
                                    @else
                                    <span class=" badge bg-success">Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($p->status_npl == 'aktif' && $p->event_lelang->waktu < $hours_now || $p->status_npl == 'aktif' && $p->event_lelang->status_data == 0)
                                    <form action="{{route('user-refund', $p->id)}}" method="POST" onsubmit="return confirm('Apakah anda yakin akan melakukan refund ?');">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="PUT">
                                    <button class="btn btn-danger" type="submit">Refund</button>
                                    </form>
                                    @else
                                    <span>-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#eventlist').on('change', function () {
                var eventId = this.value;
                $('#harganpl').html('');
                $.ajax({
                    url: "get-harganpl/" + eventId,
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
</section>
@endsection
