@extends('layouts.template')

@section('content')
<div class="container">
    <h1>{{ $page->title }}</h1>
    <p>Customer: {{ $penjualan->customer->customer_nama }}</p>
    <p>Tanggal: {{ $penjualan->tanggal }}</p>
    <p>Total Harga: {{ $penjualan->total_harga }}</p>

    <h3>Detail Penjualan</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan->penjualanDetail as $detail)
            <tr>
                <td>{{ $detail->barang->barang_nama }}</td>
                <td>{{ $detail->jumlah }}</td>
                <td>{{ $detail->harga }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
