<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lokasi;

class LokasiController extends Controller
{
    public function index()
    {
        $data['lokasi'] = Lokasi::latest()->paginate(20);
        return view('admin.lokasi_index', $data);
    }

    public function create()
    {
        $lastKodeLokasi = Lokasi::orderBy('kode_lokasi', 'desc')->first();

        if ($lastKodeLokasi) {
            $lastUrut = (int) substr($lastKodeLokasi->kode_lokasi, 4); 
            $nextUrut = str_pad($lastUrut + 1, 3, '0', STR_PAD_LEFT); 
        } else {
            $nextUrut = '001';
        }

        $kodeLokasiBaru = 'LOC-' . $nextUrut;

        return view('admin.lokasi_create', compact('kodeLokasiBaru'));
    }

    public function store(Request $request)
    {
       
        $requestData = $request->validate([
            'nama_lokasi' => 'required',
            'alamat'=> 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'radius' => 'required|numeric',
            'zona_waktu' => 'required|in:wib,wita,wit',
            'jam_masuk' => 'required',
            'jam_keluar' => 'required'
        ]);

        
        $lastKodeLokasi = Lokasi::orderBy('kode_lokasi', 'desc')->first();
        if ($lastKodeLokasi) {
            $lastUrut = (int) substr($lastKodeLokasi->kode_lokasi, 4);
            $nextUrut = str_pad($lastUrut + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextUrut = '001';
        }

        
        $kodeLokasiBaru = 'LOC-' . $nextUrut;

       
        $lokasi = new Lokasi;
        $lokasi->fill($requestData); // Mengisi objek dengan data yang sudah divalidasi
        $lokasi->kode_lokasi = $kodeLokasiBaru; // Menambahkan kode lokasi otomatis
        $lokasi->save();

        flash('Data Berhasil Disimpan')->success(); 
        return back(); 
    }

    public function edit($id)  {
        $data['lokasi'] = Lokasi::findOrFail($id);
        return view('admin.lokasi_edit', $data);

    }

    public function update(Request $request, string $id){

    $requestData = $request->validate([
            'nama_lokasi' => 'required',
            'alamat'=> 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'radius' => 'required|numeric',
            'zona_waktu' => 'required|in:wib,wita,wit',
            'jam_masuk' => 'required',
            'jam_keluar' => 'required'
        ]);

    $lokasi = \App\Models\Lokasi::findOrFail($id);
    $lokasi->fill($requestData);
    $lokasi->save();

    flash('Data Berhasil Diubah')->success();
    return back();
}

    public function show($id)
    {
        $data['lokasi'] = Lokasi::findOrFail($id);
        return view('admin.lokasi_show', $data);
    }

    public function destroy(String $id)
    {
        $daftar = Lokasi::findOrFail($id);
        $daftar->delete();
        flash('Data Berhasil Dihapus')->success();
        return back();
    }
}
