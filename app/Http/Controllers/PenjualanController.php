<?php

namespace App\Http\Controllers;

use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PenjualanController extends Controller
{
    // Menampilkan halaman daftar penjualan
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Penjualan',
            'list' => ['Home', 'Penjualan']
        ];
        $page = (object) [
            'title' => 'Daftar Penjualan yang Terdaftar dalam Sistem'
        ];
        $activeMenu = 'penjualan'; // Set menu yang aktif

        return view('penjualan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Ambil data penjualan dalam bentuk JSON untuk DataTables
    public function list(Request $request)
    {
        $penjualan = PenjualanModel::with('customer')->select('penjualan_id', 'customer_id', 'tanggal', 'total_harga');

        return DataTables::of($penjualan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($penjualan) {
                $btn = '<a href="' . url('/penjualan/' . $penjualan->penjualan_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Menampilkan detail penjualan
    public function show(string $id)
    {
        $penjualan = PenjualanModel::with('penjualanDetail.barang')->find($id);
        $breadcrumb = (object) ['title' => 'Detail Penjualan', 'list' => ['Home', 'Penjualan', 'Detail']];
        $page = (object) ['title' => 'Detail Penjualan'];
        $activeMenu = 'penjualan';

        return view('penjualan.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'penjualan' => $penjualan, 'activeMenu' => $activeMenu]);
    }

    // Menampilkan form tambah penjualan
    public function create()
    {
        $breadcrumb = (object) ['title' => 'Tambah Penjualan', 'list' => ['Home', 'Penjualan', 'Tambah']];
        $page = (object) ['title' => 'Tambah Penjualan Baru'];
        $activeMenu = 'penjualan';
        $barang = BarangModel::all(); // Ambil data barang untuk form

        return view('penjualan.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu]);
    }

    // Menyimpan penjualan baru
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'   => 'required|integer',
            'tanggal'       => 'required|date',
            'barang_id.*'   => 'required|integer',
            'jumlah.*'      => 'required|integer',
            'harga.*'       => 'required|integer'
        ]);

        $penjualan = PenjualanModel::create([
            'customer_id'   => $request->customer_id,
            'tanggal'       => $request->tanggal,
            'total_harga'   => array_sum($request->harga) // Total dari harga yang dikirim
        ]);

        foreach ($request->barang_id as $key => $barang_id) {
            PenjualanDetailModel::create([
                'penjualan_id' => $penjualan->penjualan_id,
                'barang_id'    => $barang_id,
                'jumlah'       => $request->jumlah[$key],
                'harga'        => $request->harga[$key]
            ]);
        }

        return redirect('/penjualan')->with('success', 'Data penjualan berhasil disimpan');
    }
}
