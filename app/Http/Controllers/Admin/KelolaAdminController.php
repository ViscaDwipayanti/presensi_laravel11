<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class KelolaAdminController extends Controller
{
    public function index(){
        $data['admin'] = User::where('role', 'admin')->latest()->paginate(20);
        return view('admin.kelola_admin_index', $data);
    }

    public function create(){
        return view('admin.kelola_admin_create');
    }

    public function store(Request $request){
        $requestData = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,pegawai',
        ]);

        $user = new User;
        $user->fill($requestData); // Mengisi objek dengan data yang sudah divalidasi
        $user->save();

        flash('Data Berhasil Disimpan')->success(); 
        return back(); 

    }
}
