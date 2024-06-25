@extends('app.layouts')
@section('content')
<style>
    #pending.active {
        background-color: #FFC107;
    }

    #profile-tab3.active {
        background-color: #63ED7A;
    }

    /* Mengatur warna hitam untuk tautan aktif dengan ID "contact-tab3" */
    #contact-tab3.active {
        background-color: #CDD3D8;
    }

    .nav-pills {
        gap: 1rem;
    }

</style>
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="">Daftar Pesanan</h4>
                </div>
                <div class="card-body">
                    <div class="w-25 mb-3">
                        <form action="/download-excel" method="post">
                            @csrf
                            <label for="">Download Excel</label>
                            <div style="display: flex; gap: 10px">
                                <input type="date" name="date" class="form-control" id="date">
                                <button id="dLabel" type="submit" class="btn btn-sm btn-danger"><i class="fas fa-print"></i>
                                    Download</button>
                            </div>
                        </form>
                    </div>
                    <table class="table table-striped w-100" id="tablepesanan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Invoice</th>
                                <th>Nama</th>
                                <th>Total Item</th>
                                <th>Total Harga</th>
                                <th>Ongkir</th>
                                <th>Sub Total</th>
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
        let table = $('#tablepesanan').DataTable({
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
                    data: "no_invoice",
                    render: function (data) {
                        if (data == null) {
                            a = `<span>-</span>`
                        } else {
                            a = `<span>` + data + `</span>`
                        }
                        return a;
                    }
                },

                {
                    data: "nama_user",
                },

                {
                    data: null,
                    render: function (data, row, type, meta) {
                        a = '<span>' + data.orderitem.length + '</span>'
                        return a;
                    }
                },
                {
                    data: "total_harga_all_item",
                    render: function (data, type, row, meta) {
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
                },
                {
                    data: "cost_shipping",
                    render: function (data, type, row, meta) {
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
                },
                {
                    data: "sub_total",
                    render: function (data, type, row, meta) {
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
                },
                {
                    data: "status",
                    render: function (data, type, row, meta) {
                        if (data == "PENDING") {
                            badge = `<span class="badge badge-primary">PENDING</span>`
                        } else if (data == "PAID") {
                            badge = `<span class="badge badge-success">SUCCESS</span>`
                        } else if (data == "EXPIRED") {
                            badge = `<span class="badge badge-dark">EXPIRED</span>`
                        } else if (data == "FAILED") {
                            badge = `<span class="badge badge-danger">FAILED</span>`
                        } else {
                            badge = `<span class="badge badge-danger">ERROR</span>`
                        }
                        return badge;
                    }
                },

                {
                    data: null,
                    render: function (data) {
                        var detailUrl = '/detail-pesanan/' + data.id;
                        var editUrl = '/edit-pesanan/' + data.id;
                        return `
                        <span><a class="btn btn-sm btn-dark" href="${detailUrl}"><i class="fas fa-search"></i> Detail</a></span>
                    `;
                    },
                },
            ],
        });

        $('#date').on('change', function () {
            var date = this.value;
            console.log('date', date);
            table.draw();

        });

        // $(document).on('click', '#dLabel', function (e) {
        //     $(this).hide();
        //     $('#loading').show();
        //     let date = $('#date').val();
        //     $.ajax({
        //         method: 'post',
        //         url: '/download-excel',
        //         data: {
        //             date: date,
        //         },
        //         success: function (res) {
        //             $('#dLabel').show();
        //             $('#loading').hide();
        //             if (res.file) {
        //                 // Menggunakan window.location.href untuk memulai pengunduhan file
        //                 window.location.href = res.file;
        //             } else {
        //                 iziToast.error({
        //                     title: 'Ada Kesalahan',
        //                     message: 'File tidak tersedia',
        //                     position: 'topRight'
        //                 });
        //             }

        //         },
        //         error: function (res) {
        //             $('#dLabel').show();
        //             $('#loading').hide();
        //             iziToast.error({
        //                 title: 'Ada Kesalahan',
        //                 message: res.responseJSON.error,
        //                 position: 'topRight'
        //             });
        //         }
        //     });
        // });
    });

</script>

@endsection
