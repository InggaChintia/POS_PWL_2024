@extends('layouts.app')

@section('content')
    <h1>Daftar Stok Barang</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('stok.reduce', $item->stok_id) }}" class="btn btn-warning btn-sm">Kurangi Stok</a>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Jumlah Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stok as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->barang->barang_nama }}</td>
                    <td>{{ $item->jumlah_stok }}</td>
                    <td>
                        <a href="{{ route('stok.reduce', $item->id) }}" class="btn btn-warning btn-sm">Kurangi Stok</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
