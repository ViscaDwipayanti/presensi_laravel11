<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Lokasi;
use App\Models\Jabatan;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawais = Pegawai::with(['jabatan', 'lokasi'])->latest()->paginate(10);
        return view('admin.pegawai_index', compact('pegawais'));
    }

    public function create()
    {
        $jabatans = Jabatan::all();
        $lokasis = Lokasi::all();

        // Buat NIP otomatis
        $tanggal = now(); // atau \Carbon\Carbon::now()
        $formatTahunBulan = $tanggal->format('ym'); // contoh: 2505
        $lastPegawai = Pegawai::where('nip', 'like', $formatTahunBulan . '%')->latest('nip')->first();

        $urutan = $lastPegawai ? (int)substr($lastPegawai->nip, -3) + 1 : 1;
        $nipBaru = $formatTahunBulan . str_pad($urutan, 3, '0', STR_PAD_LEFT);

        return view('admin.pegawai_create', compact('jabatans', 'lokasis', 'nipBaru'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_pegawai' => 'required|string|min:3',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'no_hp' => 'required|string',
            'id_jabatan' => 'required|exists:jabatans,id',
            'id_lokasi' => 'required|exists:lokasis,id',
            'foto' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,pegawai',
        ]);

        $tanggal = now();
        $formatTahunBulan = $tanggal->format('ym');
        $lastPegawai = Pegawai::where('nip', 'like', $formatTahunBulan . '%')->latest('nip')->first();
        $urutan = $lastPegawai ? (int)substr($lastPegawai->nip, -3) + 1 : 1;
        $nipBaru = $formatTahunBulan . str_pad($urutan, 3, '0', STR_PAD_LEFT);
        $validatedData['nip'] = $nipBaru;

        // Upload foto
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_pegawai', 'public');
            $validatedData['foto'] = $fotoPath;
        }

        // Simpan pegawai
        $pegawai = Pegawai::create($validatedData);

        // Simpan user
        User::create([
            'id_pegawai' => $pegawai->id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        flash('Data Pegawai Berhasil Disimpan dengan NIP: ' . $nipBaru);
        return redirect()->route('pegawai.index');
    }


    public function show(string $id)
    {
        // View detail pegawai jika diperlukan
    }

    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $jabatans = Jabatan::all();
        $lokasis = Lokasi::all();
        return view('admin.pegawai_edit', compact('pegawai', 'jabatans', 'lokasis'));
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $validatedData = $request->validate([
            // NIP tidak diambil dari request
            'nama_pegawai' => 'required|string|min:3',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'no_hp' => 'required|string',
            'id_jabatan' => 'required|exists:jabatans,id',
            'id_lokasi' => 'required|exists:lokasis,id',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // Tetapkan kembali nip dari data sebelumnya
        $validatedData['nip'] = $pegawai->nip;

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_pegawai', 'public');
            $validatedData['foto'] = $fotoPath;
        }

        $pegawai->update($validatedData);

        flash('Data Pegawai Berhasil Diperbarui');
        return redirect()->route('pegawai.index');
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);

        // Hapus foto jika ada
        if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
            Storage::disk('public')->delete($pegawai->foto);
        }

        $pegawai->delete();

        flash('Data Berhasil Dihapus');
        return redirect()->route('pegawai.index');
    }


}


