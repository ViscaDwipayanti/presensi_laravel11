<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jabatans = Jabatan::latest()->paginate(10);
        return view('admin.jabatan_index', compact('jabatans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.jabatan_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_jabatan' => 'required|string',
        ]);

        Jabatan::create($validatedData);

        // Gunakan session flash standar Laravel (jika kamu pakai paket flash, sesuaikan)
        return redirect()->route('jabatan.index')
            ->with('success', 'Data Jabatan Berhasil Disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        return view('admin.jabatan_edit', compact('jabatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_jabatan' => 'required|string',
        ]);

        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update($validatedData);

        return redirect()->route('jabatan.index')
            ->with('success', 'Data Jabatan Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();

        return redirect()->back()
            ->with('success', 'Data Jabatan Berhasil Dihapus');
    }
}
