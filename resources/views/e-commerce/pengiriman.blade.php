@extends('app.layouts')
@section('content')
<style>
    .nav-pills {
        display: flex;
        gap: 5px;
    }

    .countdata {
        background-color: rgba(255, 255, 255, 0.25);
    }
    .btn-progress{
        background-size: 22px !important;
    }

</style>
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="">Daftar Pengiriman</h4>
                </div>
                <div class="card-body">
                    <div class="w-25 mb-3">
                            @csrf
                            <label for="">Filter Label Pengiriman</label>
                            <div style="display: flex; gap: 10px">

                                <input type="date" name="date" class="form-control" id="date">
                                <button id="dLabel"  class="btn btn-sm btn-danger"><i class="fas fa-print"></i> Download Label</button>
                                    <button class="btn btn-secondary" id="loading" style="display: none">Sedang Mendownload...</button>
                            </div>
                    </div>
                    <table class="table table-striped w-100" id="tablepesanan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Order Id</th>
                                <th>Nomor Resi</th>
                                <th>Tgl Dibuat</th>
                                <th>Nama Penerima</th>
                                <th>Item</th>
                                <th>Ongkir</th>
                                <th>Status</th>
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
<script>
    $(document).ready(function () {
        var table = $('#tablepesanan').DataTable({
            processing: true,
            ordering: false,
            serverSide: true,
            ajax: {
                url: '{{ url()->current() }}',
                data: function (data) {
                    data.date = $('#date').val()
                }
            },
            columns: [{
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "biteship_order_id",
                    render: function (data) {
                        if (data == null) {
                            a = '<span>-</span>';
                        } else {
                            a = '<span>' + data + '</span>';
                        }
                        return a;
                    }
                },
                {
                    data: "waybill_id",
                    render: function (data) {
                        if (data == null) {
                            a = '<span>-</span>';
                        } else {
                            a = '<span>' + data + '</span>';
                        }
                        return a;
                    }

                },
                    {
                        data: "tanggal_dibuat",
                        render: function (data) {
                            if (data == null) {
                                a = '<span>-</span>';
                            } else {
                                a = '<span>' + data + '</span>';
                            }
                            return a;
                        }

                    },
                    {
                        data: "nama_order",
                        render: function (data) {
                            if (data == null) {
                                a = '<span>-</span>';
                            } else {
                                a = '<span>' + data + '</span>';
                            }
                            return a;
                        }
                    },
                    {
                        data: 'qty',
                        
                    },
                    {
                        data: "insurance_amount",
                        render: function (data, type, row, meta) {
                            if (data == null) {
                                return '<span>-</span>';
                            } else {
                                if (type === 'display') {
                                    // Mengubah data menjadi format mata uang dengan simbol IDR
                                    return "Rp " + parseInt(data).toLocaleString('id-ID', {
                                        minimumFractionDigits: 0
                                    });
                                } else {
                                    // Untuk tipe data lain, kembalikan data aslinya
                                    return data;
                                }
                            }
                        }
                    },
                    {
                        data: "status",
                        render: function (data) {
                            if (data == 'confirmed') {
                                info = 'Pesanan telah dikonfirmasi. Menemukan pengemudi terdekat untuk dijemput.';
                            } else if(data == 'allocated') {
                                info='Kurir telah dialokasikan. Menunggu untuk mengambil.';
                            }else if (data == 'pickingUp') {
                                info='Kurir sedang dalam perjalanan untuk mengambil barang.';
                            }else if (data == 'picked') {
                                info='Barang telah diambil dan siap dikirim.';
                            }else if (data == 'droppingOff') {
                                info='Item sedang dalam perjalanan ke pelanggan.';
                            }else if (data == 'returnInTransit') {
                                info='Pesanan sedang dalam perjalanan kembali ke asal.';
                            }else if (data == 'onHold') {
                                info='Pengiriman Anda sedang ditahan saat ini. Kami akan mengirimkan barang Anda setelah diselesaikan.';
                            }else if (data == 'delivered') {
                                info='Barang telah dikirim.';
                            }else if (data == 'rejected') {
                                info='Pengiriman Anda ditolak. Silakan hubungi Biteship untuk informasi lebih lanjut.'
                            }else if (data == 'courierNotFound') {
                                info='Pengiriman Anda dibatalkan karena tidak ada kurir yang tersedia saat ini.'
                            }else if (data == 'returned') {
                                info='Pesanan berhasil dikembalikan.'
                            }else if (data == 'cancelled') {
                                info='Pesanan dibatalkan.'
                            }else if(data == 'disposed'){

                                info='Pesanan berhasil dibuang.'
                            }else{
                                info='Tidak Diketahui.'
                            }

                            if (data == null) {
                                a = '<span>-</span>';
                            } else {
                                a = `<button type="button" class="btn btn-outline-primary" data-toggle="tooltip" data-placement="top" title="${info}">${data}</button>`;
                            }
                            
                            return a;
                        }
                    },
                    {
                        data: null,
                        render: function (data) {
                            return `  <a href="/detail-pengiriman/${data.id}" data-placement="top" title="detail pesanan" class="btn btn-warning">
                            <span class="text"><i class="fas fa-info"></i></span>
                        </a> 
                        <span><a href="/tracking/${data.biteship_order_id}" class="btn  btn-dark" data-toggle="tooltip" data-placement="top" title="lacak status pengiriman"><i class="fas fa-shipping-fast"></i></a></span>`;
                        }
                    }

            ],
        });

        $('#date').on('change', function () {
            var date = this.value;
            console.log('date', date);
            table.draw();

        });

        $(document).on('click', '#dLabel', function (e) {
        $(this).hide();
        $('#loading').show();
        let date = $('#date').val();
        $.ajax({
            method: 'post',
            url: '/download-pdf',
            data: {
                date: date,
            },
            success: function (res) {
                $('#dLabel').show();
                $('#loading').hide();
                window.open(res,'_blank');
                
            },
            error: function (res) {
                $('#dLabel').show();
                $('#loading').hide();
                iziToast.error({
                    title: 'Ada Kesalahan',
                    message: res.responseJSON.error,
                    position: 'topRight'
                });
            }
        });
    });

    });

</script>

@endsection
