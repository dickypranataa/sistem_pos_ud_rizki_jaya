<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Produk;

class AdminController extends Controller
{
    //
    function index(){
        $users = User::all();
        //total produk
        $produks = Produk::count();
        
        return view('admin.dashboard', compact('users', 'produks'));
    }
}
