<?php

namespace App\Http\Controllers\Pegawai;

use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RekapPresensiPegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Presensi::query();
        $query->where('id_pegawai', Auth::user()->pegawai->id);

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal_masuk', [$request->tanggal_awal, $request->tanggal_akhir]);
        } elseif ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal_masuk', $request->tanggal_awal);
        }

        $query->orderBy('created_at', 'desc');
        $models = $query->get();

        return view('pegawai.rekap_presensi_pegawai_index', compact('models'));
    }

    public function exportPdf(Request $request)
    {
        $user = Auth::user();

        $query = Presensi::with('pegawai');
        $query->where('id_pegawai', $user->pegawai->id);

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal_masuk', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_masuk', '<=', $request->tanggal_akhir);
        }

        $rekap = $query->get();

        $tanggalAwal = $request->tanggal_awal 
            ? Carbon::parse($request->tanggal_awal)->translatedFormat('d F Y') 
            : null;
        $tanggalAkhir = $request->tanggal_akhir 
            ? Carbon::parse($request->tanggal_akhir)->translatedFormat('d F Y') 
            : null;

        $pdf = PDF::loadView('pegawai.rekap_presensi_pegawai_pdf', [
            'models' => $rekap,
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
            'pegawai' => $user->pegawai
        ])->setPaper('a4', 'portrait');

        return $pdf->download('rekap-presensi-pegawai.pdf');
    }
}
