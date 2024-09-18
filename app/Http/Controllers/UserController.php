<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //JS 4 PRAKTIKUM 1
    public function index() {
        //UPDATE
        $data = [
            'level_id' => 2,
            'username' => 'manager_tiga',
            'nama' => 'Manager 3',
            'password' => Hash::make('1234')
        ];

        // $data = [
        //     'level_id' => 2,
        //     'username' => 'manager_dua',
        //     'nama' => 'Manager 2',
        //     'password' => Hash::make('1234')
        // ];
        UserModel::create($data);

        $user = UserModel::all();
        return view('user', ['data' => $user]);
    }
    
    
    // public function cust($id, $name){
    //     return view('ViewUser')
    //     ->with('id', $id)
    //     ->with('name', $name);
    // }
    

    //NO 8 PRAKTIKUM 6
    // public function index() {
    //     //tambah data user dengan Eloquent Model
    //     $data = [
    //         'username' => 'customer-1',
    //         'nama' => 'Pelanggan',
    //         'password' => Hash::make('12345'),
    //         'level_id' => 3
    //     ];
    //     UserModel::insert($data); //tambahkan data ke table m_user

    //     // NO 5 PRAKTIKUM 6
    //     //coba akses model UserModel
    //     $user = UserModel::all(); //ambil semua data dari  table m_user
    //     return view('user', ['data' => $user]);

    // }
}
