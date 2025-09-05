<?php

use App\Http\Controllers\Pegawai\RekapPresensiPegawaiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\LokasiController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KelolaAdminController;
use App\Http\Controllers\Admin\RekapAbsensiController;
use App\Http\Controllers\pegawai\KetidakhadiranController;
use App\Http\Controllers\Pegawai\PegawaiDashboardController;
use App\Http\Controllers\Admin\KetidakhadiranController as AdminKetidakhadiranController;
use App\Http\Controllers\Pegawai\RekapPresensiPegawai;
use App\Http\Controllers\Admin\RekapitulasiPresensiController;

Route::get('/', function () {
    return view('auth.login');
});

// Route untuk guest (belum login)
Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Route untuk yang sudah login (authenticated)
Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Admin dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    // Pegawai dashboard
    Route::get('/pegawai/dashboard', [PegawaiDashboardController::class, 'index'])
        ->middleware('role:pegawai')
        ->name('pegawai.dashboard');
});

// Admin routes - hanya dapat diakses oleh admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('/admin/lokasi', LokasiController::class);
    Route::resource('/admin/pegawai', PegawaiController::class);
    Route::resource('/admin/jabatan', JabatanController::class);
    Route::resource('/admin/kelola_admin', KelolaAdminController::class);


    Route::get('/admin/ketidakhadiran', [AdminKetidakhadiranController::class, 'index'])->name('admin.ketidakhadiran');
    Route::post('/admin/ketidakhadiran/{id}/setujui', [AdminKetidakhadiranController::class, 'setujui'])->name('admin.ketidakhadiran.setujui');
    Route::post('/admin/ketidakhadiran/{id}/tolak', [AdminKetidakhadiranController::class, 'tolak'])->name('admin.ketidakhadiran.tolak');
    Route::get('/ketidakhadiran/export_pdf', [AdminKetidakhadiranController::class, 'exportPdf'])->name('admin.ketidakhadiran.exportPdf');

    // Route::resource('/admin/rekap',RekapAbsensiController::class);
    Route::get('/admin/rekap', [RekapAbsensiController::class, 'index'])->name('rekap.index');
    Route::get('/admin/rekap/export-pdf', [RekapAbsensiController::class, 'exportPdf'])->name('rekap.exportPdf');

    Route::get('/rekapitulasi-presensi', [RekapitulasiPresensiController::class, 'index'])->name('rekapitulasi.presensi');
    Route::get('/admin/rekapitulasi-presensi/pdf', [RekapitulasiPresensiController::class, 'cetakPdf'])->name('rekap.presensi.pdf');

});

Route::middleware(['auth', 'role:pegawai'])->group(function () {
    Route::post('/pegawai/presensi_masuk', [PegawaiDashboardController::class, 'presensi_masuk'])->name('pegawai.presensi_masuk');
    Route::post('/pegawai/ambil_foto', [PegawaiDashboardController::class, 'presensi_masuk'])->name('pegawai.ambil_foto');
    Route::post('/pegawai/presensi_masuk_aksi', [PegawaiDashboardController::class, 'presensi_masuk_aksi'])->name('pegawai.presensi_masuk_aksi');

    Route::post('/pegawai/presensi_keluar/{id}', [PegawaiDashboardController::class, 'presensi_keluar'])->name('pegawai.presensi_keluar');
    Route::post('/pegawai/ambil_foto_keluar/{id}', [PegawaiDashboardController::class, 'presensi_keluar'])->name('pegawai.ambil_foto_keluar');
    Route::post('/pegawai/presensi_keluar_aksi/{id}', [PegawaiDashboardController::class, 'presensi_keluar_aksi'])->name('pegawai.presensi_keluar_aksi');

    Route::resource('/pegawai/ketidakhadiran', KetidakhadiranController::class);

    Route::get('/pegawai/rekap', [RekapPresensiPegawaiController::class, 'index'])->name('rekap_presensi_pegawai.index');
    Route::get('/pegawai/rekap/export-pdf', [RekapPresensiPegawaiController::class, 'exportPdf'])->name('rekap_presensi_pegawai.exportPdf');


});


