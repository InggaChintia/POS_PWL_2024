<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator; 
use PhpOffice\PhpSpreadsheet\IOFactory; 
use Yajra\DataTables\Facades\DataTables;

class BarangController extends Controller
{
    public function index()
    {
        $activeMenu = 'barang';
        $breadcrumb = (object) [
            'title' => 'Data Barang',
            'list' => ['Home', 'Barang']
        ];

        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
        return view('barang.index', [
            'activeMenu' => $activeMenu,
            'breadcrumb' => $breadcrumb,
            'kategori' => $kategori
        ]);
    }

    public function list(Request $request)
    {
        $barang = BarangModel::select('barang_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual', 'kategori_id')->with('kategori');

        $kategori_id = $request->input('filter_kategori');
        if (!empty($kategori_id)) {
            $barang->where('kategori_id', $kategori_id);
        }

        return DataTables::of($barang)
            ->addIndexColumn()
            ->addColumn('aksi', function ($barang) {
                $btn = '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
        return view('barang.create_ajax')->with('kategori', $kategori);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id' => ['required', 'integer', 'exists:m_kategori,kategori_id'],
                'barang_kode' => ['required', 'min:3', 'max:20', 'unique:m_barang,barang_kode'],
                'barang_nama' => ['required', 'string', 'max:100'],
                'harga_beli' => ['required', 'numeric'],
                'harga_jual' => ['required', 'numeric'],
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            BarangModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan'
            ]);
        }
        return redirect('/');
    }

    public function edit_ajax($id)
    {
        $barang = BarangModel::find($id);
        $level = LevelModel::select('level_id', 'level_nama')->get();
        return view('barang.edit_ajax', ['barang' => $barang, 'level' => $level]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id' => ['required', 'integer', 'exists:m_kategori,kategori_id'],
                'barang_kode' => ['required', 'min:3', 'max:20', 'unique:m_barang,barang_kode, ' . $id . ',barang_id'],
                'barang_nama' => ['required', 'string', 'max:100'],
                'harga_beli' => ['required', 'numeric'],
                'harga_jual' => ['required', 'numeric'],
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $barang = BarangModel::find($id);
            if ($barang) {
                $barang->update($request->all());
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

    public function confirm_ajax($id)
    {
        $barang = BarangModel::find($id);
        return view('barang.confirm_ajax', ['barang' => $barang]);
    }

    public function delete_ajax(Request $request, $id)
    {
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

    public function import()
    {
        return view('barang.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_barang' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_barang');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) {
                        $insert[] = [
                            'kategori_id' => $value['A'],
                            'barang_kode' => $value['B'],
                            'barang_nama' => $value['C'],
                            'harga_beli' => $value['D'],
                            'harga_jual' => $value['E'],
                            'created_at' => now(),
                        ];
                    }
                }
            }

            if (count($insert) > 0) {
                BarangModel::insertOrIgnore($insert);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diimport'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data yang diimport'
            ]);
        }
        return redirect('/');
    }
}




// class BarangController extends Controller
// {
//     public function index()
//     {
//         // Menampilkan halaman daftar barang
//         $breadcrumb = (object) [
//             'title' => 'Daftar Barang',
//             'list' => ['Home', 'Barang']
//         ];
//         $page = (object) [
//             'title' => 'Daftar barang yang terdaftar dalam sistem',
//         ];
//         $activeMenu = 'barang'; // Set menu yang sedang aktif
//         $kategori = KategoriModel::all(); // Ambil data kategori untuk filter barang
//         return view('barang.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
//     }

//     // // Ambil data barang dalam bentuk JSON untuk DataTables
//     // public function list(Request $request)
//     // {
//     //     $barangs = BarangModel::select('barang_id', 'kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')
//     //         ->with('kategori'); // Relasi dengan tabel kategori
//     //     // Filter barang berdasarkan kategori_id
//     //     if ($request->kategori_id) {
//     //         $barangs->where('kategori_id', $request->kategori_id);
//     //     }
//     //     return DataTables::of($barangs)
//     //         // Menambahkan kolom index / no urut
//     //         ->addIndexColumn()
//     //         ->addColumn('aksi', function ($barang) { // Menambahkan kolom aksi
//     //             $btn = '<a href="' . url('/barang/' . $barang->barang_id) . '" class="btn btn-info btn-sm">Detail</a> ';
//     //             $btn .= '<a href="' . url('/barang/' . $barang->barang_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
//     //             $btn .= '<form class="d-inline-block" method="POST" action="' .
//     //                 url('/barang/' . $barang->barang_id) . '">'
//     //                 . csrf_field() . method_field('DELETE') .
//     //                 '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
//     //             return $btn;
//     //         })
//     //         ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi adalah HTML
//     //         ->make(true);
//     // }

//     // Menampilkan form tambah barang
//     public function create()
//     {
//         $breadcrumb = (object) [
//             'title' => 'Tambah Barang',
//             'list' => ['Home', 'Barang', 'Tambah']
//         ];
//         $page = (object) [
//             'title' => 'Tambah barang baru',
//         ];
//         $kategori = KategoriModel::all(); // Ambil data kategori untuk form tambah barang
//         $activeMenu = 'barang'; // Set menu yang aktif
//         return view('barang.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
//     }

//     // Menyimpan data barang baru
//     public function store(Request $request)
//     {
//         $request->validate([
//             'barang_kode' => 'required|string|unique:m_barang,barang_kode',
//             'barang_nama' => 'required|string|max:100',
//             'harga_beli' => 'required|integer',
//             'harga_jual' => 'required|integer',
//             'kategori_id' => 'required|integer'
//         ]);
//         BarangModel::create([
//             'barang_kode' => $request->barang_kode,
//             'barang_nama' => $request->barang_nama,
//             'harga_beli' => $request->harga_beli,
//             'harga_jual' => $request->harga_jual,
//             'kategori_id' => $request->kategori_id
//         ]);
//         return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
//     }

//     // Menampilkan detail barang
//     public function show(string $id)
//     {
//         $barang = BarangModel::with('kategori')->find($id);
//         if (!$barang) {
//             return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
//         }
//         $breadcrumb = (object) [
//             'title' => 'Detail Barang',
//             'list' => ['Home', 'Barang', 'Detail']
//         ];
//         $page = (object) [
//             'title' => 'Detail barang',
//         ];
//         $activeMenu = 'barang'; // Set menu yang sedang aktif
//         return view('barang.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu]);
//     }

//     // Menampilkan halaman form edit barang
//     public function edit(string $id)
//     {
//         $barang = BarangModel::find($id);
//         $kategori = KategoriModel::all();
//         if (!$barang) {
//             return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
//         }
//         $breadcrumb = (object) [
//             'title' => 'Edit Barang',
//             'list' => ['Home', 'Barang', 'Edit']
//         ];
//         $page = (object) [
//             'title' => 'Edit barang',
//         ];
//         $activeMenu = 'barang'; // Set menu yang aktif
//         return view('barang.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
//     }

//     // Menyimpan perubahan data barang
//     public function update(Request $request, string $id)
//     {
//         $request->validate([
//             'barang_kode' => 'required|string|unique:m_barang,barang_kode,' . $id . ',barang_id',
//             'barang_nama' => 'required|string|max:100',
//             'harga_beli' => 'required|integer',
//             'harga_jual' => 'required|integer',
//             'kategori_id' => 'required|integer'
//         ]);
//         BarangModel::find($id)->update([
//             'barang_kode' => $request->barang_kode,
//             'barang_nama' => $request->barang_nama,
//             'harga_beli' => $request->harga_beli,
//             'harga_jual' => $request->harga_jual,
//             'kategori_id' => $request->kategori_id
//         ]);
//         return redirect('/barang')->with('success', 'Data barang berhasil diubah');
//     }

//     // Menghapus data barang
//     public function destroy(string $id)
//     {
//         $barang = BarangModel::find($id);
//         if (!$barang) {
//             return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
//         }
//         try {
//             BarangModel::destroy($id);
//             return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
//         } catch (\Illuminate\Database\QueryException $e) {
//             return redirect('/barang')->with('error', 'Data barang gagal dihapus karena terkait dengan data lain');
//         }
//     }

//     public function create_ajax()
//     {
//         $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
//         return view('barang.create_ajax')
//             ->with('kategori', $kategori);
//     }

//     // Ambil data user dalam bentuk json untuk datatables 
//     public function list(Request $request)
//     {
//         $barang = BarangModel::select('kategori_id', 'barang_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual');
//         return DataTables::of($barang)
//             ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex) 
//             ->addColumn('aksi', function ($barang) { // menambahkan kolom aksi
//                 $btn = '<a href="' . url('/barang/' . $barang->barang_id) . '" class="btn btn-info btn-sm">Detail</a> ';
//                 $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
//                 $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
//                 return $btn;
//             })
//             ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html 
//             ->make(true);
//     }

//     public function update_ajax(Request $request, $id)
//     {
//         // cek apakah request dari ajax
//         if ($request->ajax() || $request->wantsJson()) {
//             $rules = [
//                 'kategori_id'   => 'required|integer',
//                 'barang_kode'   => 'required|string|min:3|unique:m_barang,barang_kode',
//                 'barang_nama'   => 'required|string|max:100', //nama harus diisi, berupa string, dan maksimal 100 karakter
//                 'harga_beli'    => 'required|integer', //nama harus diisi, berupa string, dan maksimal 100 karakter
//                 'harga_jual'    => 'required|integer', //
//             ];
//             // use Illuminate\Support\Facades\Validator;
//             $validator = Validator::make($request->all(), $rules);
//             if ($validator->fails()) {
//                 return response()->json([
//                     'status' => false, // respon json, true: berhasil, false: gagal
//                     'message' => 'Validasi gagal.',
//                     'msgField' => $validator->errors() // menunjukkan field mana yang error
//                 ]);
//             }
//             $check = BarangModel::find($id);
//             if ($check) {
//                 $check->update($request->all());
//                 return response()->json([
//                     'status' => true,
//                     'message' => 'Data berhasil diupdate'
//                 ]);
//             } else {
//                 return response()->json([
//                     'status' => false,
//                     'message' => 'Data tidak ditemukan'
//                 ]);
//             }
//         }
//         return redirect('/');
//     }

//     public function confirm_ajax(string $id)
//     {
//         $barang = BarangModel::find($id);
//         return view('barang.confirm_ajax', ['barang' => $barang]);
//     }

//     public function delete_ajax(Request $request, $id)
//     {
//         // cek apakah request dari ajax
//         if ($request->ajax() || $request->wantsJson()) {
//             $barang = BarangModel::find($id);
//             if ($barang) {
//                 $barang->delete();
//                 return response()->json([
//                     'status' => true,
//                     'message' => 'Data berhasil dihapus'
//                 ]);
//             } else {
//                 return response()->json([
//                     'status' => false,
//                     'message' => 'Data tidak ditemukan'
//                 ]);
//             }
//         }
//         return redirect('/');
//     }

//     // Menampilkan halaman form edit barang ajax
//     public function edit_ajax(string $id)
//     {
//         $barang = BarangModel::find($id);
//         $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
//         return view('barang.edit_ajax', ['barang' => $barang, 'kategori' => $kategori]);
//     }

//     public function store_ajax(Request $request)
//     {
//         // cek apakah request berupa ajax
//         if ($request->ajax() || $request->wantsJson()) {
//             $rules = [
//                 'kategori_id'   => 'required|integer',
//                 'barang_kode'   => 'required|string|min:3|unique:m_barang,barang_kode',
//                 'barang_nama'   => 'required|string|max:100', //nama harus diisi, berupa string, dan maksimal 100 karakter
//                 'harga_beli'    => 'required|integer', //nama harus diisi, berupa string, dan maksimal 100 karakter
//                 'harga_jual'    => 'required|integer', //
//             ];
//             // use Illuminate\Support\Facades\Validator;
//             $validator = Validator::make($request->all(), $rules);
//             if ($validator->fails()) {
//                 return response()->json([
//                     'status'    => false, // response status, false: error/gagal, true: berhasil
//                     'message'   => 'Validasi Gagal',
//                     'msgField'  => $validator->errors(), // pesan error validasi
//                 ]);
//             }
//             BarangModel::create($request->all());
//             return response()->json([
//                 'status'    => true,
//                 'message'   => 'Data barang berhasil disimpan'
//             ]);
//         }
//         redirect('/');
//     }
// }