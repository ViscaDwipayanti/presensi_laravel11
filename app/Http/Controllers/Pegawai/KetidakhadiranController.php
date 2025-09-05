<?php

namespace App\Http\Controllers\pegawai;


use Illuminate\Http\Request;
use App\Models\Ketidakhadiran;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KetidakhadiranController extends Controller
{
    public function index()
    {
        $id = Auth::id();
        $ketidakhadiran = Ketidakhadiran::where('id_user', $id)->paginate(10);
        return view('pegawai.ketidakhadiran_index', compact('ketidakhadiran'));
    }

    public function create()   {
        return view('pegawai.ketidakhadiran_create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'required|string',
            'lampiran' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:2048',  
        ], [
            'lampiran.max' => 'File lampiran tidak boleh lebih dari 2 MB.',  
            'lampiran.mimes' => 'Lampiran harus berupa file PDF, JPG, atau PNG.',
        ]);

        

        $path = null;
        if ($request->hasFile('lampiran')) {
            $path = $request->file('lampiran')->store('lampiran_ketidakhadiran', 'public');
        }

        $id = Auth::id();

        Ketidakhadiran::create([
            'id_user' => $id,
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'keterangan' => $validated['keterangan'],
            'lampiran' => $path,
            'status' => 'pending',
        ]);

        flash('Pengajuan ketidakhadiran berhasil dikirim')->success();
        return redirect()->route('ketidakhadiran.index');
    }

        public function edit($id)  {
        $data['ketidakhadiran'] = Ketidakhadiran::findOrFail($id);
        return view('pegawai.ketidakhadiran_edit', $data);

    }

    public function update(Request $request, $id)
    {
        $ketidakhadiran = Ketidakhadiran::findOrFail($id);


        $validated = $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'required|string',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        // Update lampiran jika ada file baru diupload
       if ($request->hasFile('lampiran')) {
            if ($ketidakhadiran->lampiran && Storage::disk('public')->exists($ketidakhadiran->lampiran)) {
                Storage::disk('public')->delete($ketidakhadiran->lampiran);
            }
            $path = $request->file('lampiran')->store('lampiran_ketidakhadiran', 'public');
            $ketidakhadiran->lampiran = $path;
        }

        $ketidakhadiran->tanggal_mulai = $validated['tanggal_mulai'];
        $ketidakhadiran->tanggal_selesai = $validated['tanggal_selesai'];
        $ketidakhadiran->keterangan = $validated['keterangan'];
        $ketidakhadiran->status = 'pending'; 


        $ketidakhadiran->save();
        flash('Data ketidakhadiran berhasil diperbarui')->success();
        return redirect()->route('ketidakhadiran.index');
    }

    public function destroy(String $id)
    {
        $daftar = Ketidakhadiran::findOrFail($id);

        if($daftar->status !=='pending'){
            flash('Pengajuan yang sudah disetujui atau ditolak tidak dapat dihapus')->error();
            return back();
        }else{
            $daftar->delete();
            flash('Data Berhasil Dihapus')->success();
            return back();
        }

        
    }
  
}
