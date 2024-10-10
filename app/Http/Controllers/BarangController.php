<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Barang',
            'list' => ['Home', 'Barang']
        ];
        $page = (object) [
            'title' => 'Daftar barang yang terdaftar dalam sistem'
        ];
        $activeMenu = 'barang'; // set menu yang sedang aktif
        $kategori = KategoriModel::all(); // ambil data kategori untuk filter kategori
        return view('barang.index', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'kategori' => $kategori,
            'activeMenu' => $activeMenu]);
    }
    // public function list(Request $request)
    // {
    //     $barangs = BarangModel::select('barang_id', 'barang_kode', 'barang_nama', 'fk_kategori_id')
    //         ->with('kategori');
    //     // Filter data barang berdasarkan kategori_id
    //     if ($request->kategori_id) {
    //         $barangs->where('fk_kategori_id', $request->kategori_id);
    //     }
    //     return DataTables::of($barangs)
    //         ->addIndexColumn()
    //         ->addColumn('aksi', function ($barang) { // menambahkan kolom aksi
    //             $btn = '<a href="' . url('/barang/' . $barang->barang_id) . '" class="btn btn-info btn-sm">Detail</a>';
    //             $btn .= '<a href="' . url('/barang/' . $barang->barang_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a>';
    //             $btn .= '<form class="d-inline-block" method="POST" action="' . url('/barang/' . $barang->barang_id) . '">
    //                         ' . csrf_field() . '
    //                         <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button>
    //                     </form>';
    //             return $btn;
    //         })
    //         ->rawColumns(['aksi']) 
    //         ->make(true);
    // }
  
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah barang',
            'list' => ['Home', 'barang', 'Tambah']
        ];
        $page = (object) [
            'title' => 'Tambah barang baru'
        ];
        $kategori = KategoriModel::all(); 
        $activeMenu = 'barang'; 
        return view('barang.create', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'kategori' => $kategori, 
            'activeMenu' => $activeMenu]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'barang_kode' => 'required|string|max:10|unique:m_barang,barang_kode',
            'barang_nama' => 'required|string|max:100',
            'fk_kategori_id' => 'required|integer',
            'harga_jual' => 'required|integer',
            'harga_beli' => 'required|integer'
        ]);
        BarangModel::create([
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'fk_kategori_id' => $request->fk_kategori_id,
            'harga_jual' => $request->harga_jual,
            'harga_beli' => $request->harga_beli
        ]);
        return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
    }
    
    public function show(string $id)
    {
        $barang = BarangModel::with('kategori')->find($id);
        $breadcrumb = (object) [
            'title' => 'Detail barang',
            'list' => ['Home', 'Barang', 'Detail']
        ];
        $page = (object) [
            'title' => 'Detail barang'
        ];
        $activeMenu = 'barang'; // set menu yang sedang aktif
        return view('barang.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu]);
    }
    // Menampilkan halaman form edit barang
    public function edit(string $id)
    {
        $barang = BarangModel::find($id);
        $kategori = KategoriModel::all();
        $breadcrumb = (object) [
            'title' => 'Edit barang',
            'list' => ['Home', 'barang', 'Edit']
        ];
        $page = (object) [
            'title' => 'Edit barang'
        ];
        $activeMenu = 'barang'; // set menu yang sedang aktif
        return view('barang.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }
    // Menyimpan perubahan data barang
    public function update(Request $request, string $id)
    {
    $request->validate([
        'barang_kode' => 'required|string|max:10',
        'barang_nama' => 'required|string|max:100',
        'fk_kategori_id' => 'required|integer',
        'harga_jual' => 'required|integer',
        'harga_beli' => 'required|integer'
    ]);
    // Update data barang
    $barang = BarangModel::find($id);
    if (!$barang) {
        return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
    }
    $barang->update([
        'barang_kode' => $request->barang_kode,
        'barang_nama' => $request->barang_nama,
        'fk_kategori_id' => $request->fk_kategori_id,
        'harga_jual' => $request->harga_jual,
        'harga_beli' => $request->harga_beli
    ]);
    return redirect('/barang')->with('success', 'Data barang berhasil diubah');
    }
    // Menghapus data barang
    public function destroy(string $id)
    {
        $check = BarangModel::find($id);
        if (!$check) {
            // untuk mengecek apakah data barang dengan id yang dimaksud ada atau tidak
            return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
        }
        try {
            BarangModel::destroy($id); // Hapus data kategori
            return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/barang')->with('error', 'Data barang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }



    //JS 6 TUGAS PRAKTIKUM
    public function create_ajax()
    {
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
        return view('barang.create_ajax')
            ->with('kategori', $kategori);
    }
    // Ambil data user dalam bentuk json untuk datatables 
    
    public function list(Request $request)
    {
        $barang = BarangModel::select('kategori_id', 'barang_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual');
        return DataTables::of($barang)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex) 
            ->addColumn('aksi', function ($barang) { // menambahkan kolom aksi
                $btn = '<a href="' . url('/barang/' . $barang->barang_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html 
            ->make(true);
    }
    public function update_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id'   => 'required|integer',
                'barang_kode'   => 'required|string|min:3|unique:m_barang,barang_kode',
                'barang_nama'   => 'required|string|max:100', //nama harus diisi, berupa string, dan maksimal 100 karakter
                'harga_beli'    => 'required|integer', //nama harus diisi, berupa string, dan maksimal 100 karakter
                'harga_jual'    => 'required|integer', //
            ];
            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // respon json, true: berhasil, false: gagal
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors() // menunjukkan field mana yang error
                ]);
            }
            $check = BarangModel::find($id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
    public function confirm_ajax(string $id)
    {
        $barang = BarangModel::find($id);
        return view('barang.confirm_ajax', ['barang' => $barang]);
    }
    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $barang = BarangModel::find($id);
            if ($barang) {
                $barang->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
    // Menampilkan halaman form edit barang ajax
    public function edit_ajax(string $id)
    {
        $barang = BarangModel::find($id);
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
        return view('barang.edit_ajax', ['barang' => $barang, 'kategori' => $kategori]);
    }
    public function store_ajax(Request $request)
    {
        // cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id'   => 'required|integer',
                'barang_kode'   => 'required|string|min:3|unique:m_barang,barang_kode',
                'barang_nama'   => 'required|string|max:100', //nama harus diisi, berupa string, dan maksimal 100 karakter
                'harga_beli'    => 'required|integer', //nama harus diisi, berupa string, dan maksimal 100 karakter
                'harga_jual'    => 'required|integer', //
            ];
            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false, // response status, false: error/gagal, true: berhasil
                    'message'   => 'Validasi Gagal',
                    'msgField'  => $validator->errors(), // pesan error validasi
                ]);
            }
            BarangModel::create($request->all());
            return response()->json([
                'status'    => true,
                'message'   => 'Data barang berhasil disimpan'
            ]);
        }
        redirect('/');
    }
}