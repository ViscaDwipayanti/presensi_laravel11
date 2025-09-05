<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::now()->toDateString();

        $total_pegawai = DB::table('pegawais')->count();
        $presensi_perhari = DB::table('presensis')->where('tanggal_masuk', $today)->count();
        $ketidakhadiran_perhari = DB::table('ketidakhadirans')
        ->whereDate('tanggal_mulai', '<=', $today)
        ->whereDate('tanggal_selesai', '>=', $today)
        ->where('status', 'disetujui')
        ->count();
        $alpa = $total_pegawai - $presensi_perhari - $ketidakhadiran_perhari;

        $pegawai_alpa = DB::table('pegawais')
            ->leftJoin('presensis', function ($join) use ($today) {
                $join->on('pegawais.id', '=', 'presensis.id_pegawai')
                    ->whereDate('presensis.tanggal_masuk', '=', $today);
            })
            ->leftJoin('users', 'users.id_pegawai', '=', 'pegawais.id') // perbaikan relasi
            ->leftJoin('ketidakhadirans', function ($join) use ($today) {
                $join->on('users.id', '=', 'ketidakhadirans.id_user')
                    ->whereDate('ketidakhadirans.tanggal_mulai', '<=', $today)
                    ->whereDate('ketidakhadirans.tanggal_selesai', '>=', $today)
                    ->where('ketidakhadirans.status', '=', 'disetujui');
            })
            ->leftJoin('jabatans', 'pegawais.id_jabatan', '=', 'jabatans.id')
            ->whereNull('presensis.id') // tidak absen hari ini
            ->whereNull('ketidakhadirans.id') // tidak punya izin yang disetujui
            ->select('pegawais.*', 'jabatans.nama_jabatan')
            ->get();





        return view('admin.dashboard', compact('total_pegawai', 'presensi_perhari', 'ketidakhadiran_perhari', 'alpa', 'pegawai_alpa')); // atau sesuaikan view-nya
    }
}
