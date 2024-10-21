<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\StokModel;
use Illuminate\Http\Request;

class StokController extends Controller
{
    // Tampilkan daftar stok barang
    public function index()
    {
        // Ambil semua data stok dari model
        $stok = StokModel::all();
        return view('stok.index', compact('stok'));
    }

    // Form tambah stok barang
    public function create()
    {
        $barang = BarangModel::all();
        return view('stok.create', compact('barang'));
    }

    // Proses penambahan stok barang
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,barang_id',
            'jumlah_stok' => 'required|integer|min:1',
        ]);

        // Cek apakah barang sudah ada stok sebelumnya
        $stokBarang = StokModel::firstOrNew(['barang_id' => $request->barang_id]);
        $stokBarang->jumlah_stok += $request->jumlah_stok;
        $stokBarang->save();

        return redirect()->route('stok.index')->with('success', 'Stok berhasil ditambahkan');
    }

    // Form pengurangan stok barang
    public function reduce($id)
    {
        $stokBarang = StokModel::findOrFail($id);
        return view('stok.reduce', compact('stokBarang'));
    }

    // Proses pengurangan stok barang
    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah_stok' => 'required|integer|min:1',
        ]);

        $stokBarang = StokModel::findOrFail($id);

        if ($stokBarang->jumlah_stok < $request->jumlah_stok) {
            return back()->withErrors('Jumlah stok yang dikurangi melebihi stok yang tersedia.');
        }

        $stokBarang->jumlah_stok -= $request->jumlah_stok;
        $stokBarang->save();

        return redirect()->route('stok.index')->with('success', 'Stok berhasil dikurangi');
    }
}
