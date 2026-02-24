<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //
    public function index(){
        $users = User::query()->paginate(10);
        return view('admin.user.index', compact('users'));
    }

    public function create(){
        return view('admin.user.create');
    }

    public function store(Request $request){
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,kasir',
        ]);

        User::create($validate);

        return redirect()->route('admin.user.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id){
        $users = User::find($id);
        return view('admin.user.edit', compact('users'));
    }

    public function update(Request $request, $id){
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,kasir',
        ]);

        User::find($id)->update($validate);

        return redirect()->route('admin.user.index')->with('success', 'User berhasil diupdate');
    }

    public function destroy($id){
        $users = User::find($id);
        $users->delete();

        return redirect()->route('admin.user.index')->with('success', 'User berhasil dihapus');
    }
}
