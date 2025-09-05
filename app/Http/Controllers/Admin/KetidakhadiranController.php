<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Ketidakhadiran;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KetidakhadiranController extends Controller
{
    public function index(){
        $data ['ketidakhadiran'] = Ketidakhadiran::latest()->paginate(10);
        return view('admin.ketidakhadiran_index', $data);
    }

    public function setujui($id) 
    {
        $item = Ketidakhadiran::findOrFail($id);
        $item->update(['status' => 'disetujui']);
        return back()->with('success', 'Pengajuan disetujui.');
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:255'
        ]);

        $item = Ketidakhadiran::findOrFail($id);
        $item->update(['status' => 'ditolak']);
        $item->alasan_penolakan = $request->alasan_penolakan;
        $item->save();
        return redirect()->back()->with('success', 'Pengajuan ditolak.');
    }

    public function exportPdf(Request $request)
    {
        $query = Ketidakhadiran::with('user.pegawai');

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('created_at', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        }

        $ketidakhadiran = $query->get();

        $tanggalAwal = $request->tanggal_awal 
        ? Carbon::parse($request->tanggal_awal)->translatedFormat('d F Y') 
        : null;
        $tanggalAkhir = $request->tanggal_akhir 
        ? Carbon::parse($request->tanggal_akhir)->translatedFormat('d F Y') 
        : null;


        $pdf = Pdf::loadView('admin.ketidakhadiran_pdf', compact('ketidakhadiran', 'tanggalAwal', 'tanggalAkhir'))
                ->setPaper('a4', 'potrait');

        return $pdf->download('laporan-ketidakhadiran.pdf');
    }

}
