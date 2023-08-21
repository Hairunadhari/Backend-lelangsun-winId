@extends('app.layouts')
@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="">Daftar Pesanan</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped w-100" id="tablepesanan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Invoice ID</th>
                                <th>Nama Pemesan</th>
                                <th>Status</th>
                                <th>Sub Total</th>
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
        $('#tablepesanan').DataTable({
            processing: true,
            ordering: false,
            serverSide: true,
            ajax: '{{ url()->current() }}',
            columns: [{
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "tagihan.external_id"
                },
                {
                    data: "user.name",
                },
                {
                    data: "tagihan.status",
                    render: function(data, type, row, meta) {
                        if (data == "PENDING") {
                            badge = `<span class="badge badge-warning">PENDING</span>`
                        } else if (data == "PAID") {
                            badge = `<span class="badge badge-success">PAID</span>`
                        } else if(data == "EXPIRED"){
                            badge = `<span class="badge badge-dark">EXPIRED</span>`
                        } else if(data == "FAILED"){
                            badge = `<span class="badge badge-danger">ERROR</span>`
                        } else if(data == "ERROR"){
                            badge = `<span class="badge badge-danger">ERROR</span>`
                        }
                        return badge;
                    }
                },
                {
                    data: "tagihan.total_pembayaran",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return parseInt(data).toLocaleString('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                            });
                        }
                        return data;
                    }
                },
                {
                data: null,
                render: function (data) {
                    var detailUrl = '/detail-pesanan/' + data.id;
                    return `
                        <span><a class="btn btn-primary" href="${detailUrl}"><i class="fas fa-search"></i> Detail</a></span>
                    `;
                },
            },
            ],
        });
    });


</script>
@endsection
