<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function cust($id, $name){
        return view('ViewUser')
        ->with('id', $id)
        ->with('name', $name);
    }
}
