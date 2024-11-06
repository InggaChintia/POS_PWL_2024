@extends('layouts.template')

@section('content')
<div class="container">
    <h1>{{ $page->title }}</h1>
    <form action="{{ url('penjualan') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="customer_id">Customer</label>
            <input type="text" class="form-control" name="customer_id" required>
        </div>
        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" class="form-control" name="tanggal" required>
        </div>

        <h3>Barang</h3>
        <div id="barang-list">
            <div class="form-group">
                <select name="barang_id[]" class="form-control" required>
                    @foreach($barang as $item)
                    <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                    @endforeach
                </select>
                <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" required>
                <input type="number" name="harga[]" class="form-control" placeholder="Harga" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
