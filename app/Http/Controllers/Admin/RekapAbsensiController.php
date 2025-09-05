<?php

namespace App\Http\Controllers\Admin;

use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class RekapAbsensiController extends Controller
{ 
    public function index(Request $request)
    {
        $query = Presensi::query();

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal_masuk', [$request->tanggal_mulai, $request->tanggal_selesai]);
        } elseif ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_masuk', $request->tanggal_mulai);
        }

        $query->orderBy('created_at', 'desc');

        $models = $query->get();

        return view('admin.rekap_absensi_index', compact('models'));
    }



public function exportPdf(Request $request)
{
    $query = Presensi::with('pegawai');

    if ($request->filled('tanggal_awal')) {
        $query->whereDate('created_at', '>=', $request->tanggal_awal);
    }

    if ($request->filled('tanggal_akhir')) {
        $query->whereDate('created_at', '<=', $request->tanggal_akhir);
    }

    $rekap = $query->get();

    $tanggalAwal = $request->tanggal_awal 
        ? Carbon::parse($request->tanggal_awal)->translatedFormat('d F Y') 
        : null;
    $tanggalAkhir = $request->tanggal_akhir 
        ? Carbon::parse($request->tanggal_akhir)->translatedFormat('d F Y') 
        : null;

    $pdf = PDF::loadView('admin.rekap_absensi_pdf', [
        'models' => $rekap,
        'tanggalAwal' => $tanggalAwal,
        'tanggalAkhir' => $tanggalAkhir
    ])->setPaper('a4', 'portrait');

    return $pdf->download('rekap-presensi.pdf');
}

    
}
