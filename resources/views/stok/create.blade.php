@extends('layouts.app')

@section('content')
    <h1>Tambah Stok Barang</h1>

    <form action="{{ route('stok.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="barang_id">Pilih Barang</label>
            <select name="barang_id" id="barang_id" class="form-control">
                @foreach ($barang as $item)
                    <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="jumlah_stok">Jumlah Stok</label>
            <input type="number" name="jumlah_stok" id="jumlah_stok" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Tambah Stok</button>
    </form>
@endsection
