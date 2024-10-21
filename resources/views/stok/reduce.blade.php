@extends('layouts.app')

@section('content')
    <h1>Kurangi Stok Barang</h1>

    <form action="{{ route('stok.update', $stokBarang->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="barang_nama">Nama Barang</label>
            <input type="text" id="barang_nama" class="form-control" value="{{ $stokBarang->barang->barang_nama }}" disabled>
        </div>

        <div class="form-group">
            <label for="jumlah_stok">Jumlah Stok Saat Ini</label>
            <input type="text" id="jumlah_stok" class="form-control" value="{{ $stokBarang->jumlah_stok }}" disabled>
        </div>

        <div class="form-group">
            <label for="jumlah_stok">Kurangi Stok</label>
            <input type="number" name="jumlah_stok" id="jumlah_stok" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-danger">Kurangi Stok</button>
    </form>
@endsection
