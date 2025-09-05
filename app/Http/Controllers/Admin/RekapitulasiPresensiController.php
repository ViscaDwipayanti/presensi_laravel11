<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Jabatan;
use App\Models\Lokasi;
use App\Models\Presensi;
use App\Models\Ketidakhadiran;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RekapitulasiPresensiController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $pegawaiId = $request->input('pegawai');
        $jabatanId = $request->input('jabatan');
        $lokasiId = $request->input('lokasi');

        $pegawais = Pegawai::orderBy('nama_pegawai')->get();
        $jabatans = Jabatan::orderBy('nama_jabatan')->get();
        $lokasis = Lokasi::orderBy('nama_lokasi')->get();

        $rekapitulasi = Pegawai::with('jabatan')
            ->when($pegawaiId, fn($q) => $q->where('id', $pegawaiId))
            ->when($jabatanId, fn($q) => $q->where('id_jabatan', $jabatanId))
            ->when($lokasiId, fn($q) => $q->where('id_lokasi', $lokasiId))
            ->get()
            ->map(function ($pegawai) use ($bulan, $tahun) {
                // Hitung kehadiran pegawai (jumlah presensi)
               $hadir = Presensi::where('id_pegawai', $pegawai->id)
                ->whereYear('tanggal_masuk', $tahun)
                ->whereMonth('tanggal_masuk', $bulan)
                ->whereNotNull('jam_masuk')
                ->whereNotNull('jam_keluar')
                ->get()
                ->filter(function ($presensi) {
                    return Carbon::parse($presensi->tanggal_masuk)->isWeekday();
                })
                ->count();


                // Hitung terlambat
                $terlambat = Presensi::where('id_pegawai', $pegawai->id)
                    ->whereYear('tanggal_masuk', $tahun)
                    ->whereMonth('tanggal_masuk', $bulan)
                    ->where('terlambat', '>', 0)
                    ->count();

                // Hitung izin dan sakit
                $jumlahKetidakhadiran = $this->getJumlahKetidakhadiran($pegawai, $bulan, $tahun);
                $izin = $jumlahKetidakhadiran['izin'];
                $sakit = $jumlahKetidakhadiran['sakit'];

                // Hitung alpha: jumlah hari kerja (hari kerja di bulan) di mana pegawai tidak hadir dan tidak izin/sakit
                $alpha = $this->hitungAlpha($pegawai, $bulan, $tahun);

                // Hitung total hari kerja pegawai yang dianggap sesuai kehadiran (jumlah hari hadir saja)
                // Jika ingin total hari kerja adalah hari kerja yang ada (bukan sesuai kehadiran), gunakan hitungHariKerja
                $totalHariKerjaPegawai = $hadir;

                return (object)[
                    'pegawai' => $pegawai,
                    'hadir' => $hadir,
                    'terlambat' => $terlambat,
                    'izin' => $izin,
                    'sakit' => $sakit,
                    'alpha' => $alpha,
                    'total_hari_kerja' => $totalHariKerjaPegawai,
                ];
            });

        return view('admin.rekapitulasi_presensi', compact('rekapitulasi', 'pegawais', 'jabatans', 'lokasis'));
    }

    private function hitungHariKerja($bulan, $tahun)
    {
        $awal = Carbon::createFromDate($tahun, $bulan, 1);
        $akhir = $awal->copy()->endOfMonth();
        $hariKerja = 0;

        for ($date = $awal; $date->lte($akhir); $date->addDay()) {
            if ($date->isWeekday()) {
                $hariKerja++;
            }
        }

        return $hariKerja;
    }

    private function getJumlahKetidakhadiran($pegawai, $bulan, $tahun)
    {
        $izin = 0;
        $sakit = 0;
        $id_user = optional($pegawai->user)->id;

        if ($id_user) {
            $ketidakhadiran = Ketidakhadiran::where('id_user', $id_user)
                ->where('status', 'disetujui')
                ->where(function ($query) use ($bulan, $tahun) {
                    $query->whereYear('tanggal_mulai', $tahun)
                          ->whereMonth('tanggal_mulai', $bulan);
                })
                ->get();

            foreach ($ketidakhadiran as $item) {
                $jenis = strtolower($item->keterangan);
                $start = Carbon::parse($item->tanggal_mulai);
                $end = Carbon::parse($item->tanggal_selesai);
                // Hitung jumlah hari kerja selama ketidakhadiran
                $jumlahHari = $start->diffInDaysFiltered(fn($date) => $date->isWeekday(), $end) + 1;

                if (str_contains($jenis, 'izin')) {
                    $izin += $jumlahHari;
                } elseif (str_contains($jenis, 'sakit')) {
                    $sakit += $jumlahHari;
                }
            }
        }

        return compact('izin', 'sakit');
    }

   private function hitungAlpha($pegawai, $bulan, $tahun)
{
    $id_pegawai = $pegawai->id;
    $id_user = optional($pegawai->user)->id;

    $awal = Carbon::createFromDate($tahun, $bulan, 1);
    $akhir = $awal->copy()->endOfMonth();

    $alpha = 0;

    for ($date = $awal->copy(); $date->lte($akhir); $date->addDay()) {
        if (!$date->isWeekday()) {
            continue; // Lewati Sabtu dan Minggu
        }

        $tanggal = $date->toDateString();

        $presensi = Presensi::where('id_pegawai', $id_pegawai)
            ->whereDate('tanggal_masuk', $tanggal)
            ->first();

        // Cek apakah ada presensi dan apakah lengkap (ada jam masuk & jam keluar)
        $hadirLengkap = $presensi && $presensi->jam_masuk && $presensi->jam_keluar;

        // Cek izin/sakit
        $adaIzinSakit = false;
        if ($id_user) {
            $adaIzinSakit = Ketidakhadiran::where('id_user', $id_user)
                ->where('status', 'disetujui')
                ->whereDate('tanggal_mulai', '<=', $tanggal)
                ->whereDate('tanggal_selesai', '>=', $tanggal)
                ->exists();
        }

        // Jika tidak hadir lengkap dan tidak izin/sakit → alpha
        if (!$hadirLengkap && !$adaIzinSakit) {
            $alpha++;
        }
    }

    return $alpha;
}


    public function cetakPdf(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $pegawaiId = $request->input('pegawai');
        $jabatanId = $request->input('jabatan');
        $lokasiId = $request->input('lokasi');

        $rekapitulasi = Pegawai::with('jabatan')
            ->when($pegawaiId, fn($q) => $q->where('id', $pegawaiId))
            ->when($jabatanId, fn($q) => $q->where('id_jabatan', $jabatanId))
            ->when($lokasiId, fn($q) => $q->where('id_lokasi', $lokasiId))
            ->get()
            ->map(function ($pegawai) use ($bulan, $tahun) {
                $hadir = Presensi::where('id_pegawai', $pegawai->id)
                ->whereYear('tanggal_masuk', $tahun)
                ->whereMonth('tanggal_masuk', $bulan)
                ->whereNotNull('jam_masuk')
                ->whereNotNull('jam_keluar')
                ->get()
                ->filter(function ($presensi) {
                 return Carbon::parse($presensi->tanggal_masuk)->isWeekday();
                })
                ->count();


                $terlambat = Presensi::where('id_pegawai', $pegawai->id)
                    ->whereYear('tanggal_masuk', $tahun)
                    ->whereMonth('tanggal_masuk', $bulan)
                    ->where('terlambat', '>', 0)
                    ->count();

                $jumlahKetidakhadiran = $this->getJumlahKetidakhadiran($pegawai, $bulan, $tahun);
                $izin = $jumlahKetidakhadiran['izin'];
                $sakit = $jumlahKetidakhadiran['sakit'];

                $alpha = $this->hitungAlpha($pegawai, $bulan, $tahun);

                // Sama seperti di index: total hari kerja berdasarkan kehadiran
                $totalHariKerjaPegawai = $hadir;

                return (object)[
                    'pegawai' => $pegawai,
                    'hadir' => $hadir,
                    'terlambat' => $terlambat,
                    'izin' => $izin,
                    'sakit' => $sakit,
                    'alpha' => $alpha,
                    'total_hari_kerja' => $totalHariKerjaPegawai,
                ];
            });

        $pdf = Pdf::loadView('admin.rekapitulasi_presensi_pdf', compact('rekapitulasi', 'bulan', 'tahun'));
        $fileName = 'rekap-presensi-' . $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($fileName);
    }
}
