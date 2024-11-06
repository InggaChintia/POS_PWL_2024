@extends('layouts.template')

@section('content')
<div class="container">
    <h1>{{ $page->title }}</h1>
    <table id="t_penjualan" class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Customer</th>
                <th>Tanggal</th>
                <th>Total Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    $('#t_penjualan').DataTable({
        serverSide: true,
        processing: true,
        ajax: "{{ url('penjualan/list') }}",
        columns: [
            { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
            { data: "customer.customer_nama" },
            { data: "tanggal" },
            { data: "total_harga" },
            { data: "aksi", orderable: false, searchable: false }
        ]
    });
});
</script>
@endsection
