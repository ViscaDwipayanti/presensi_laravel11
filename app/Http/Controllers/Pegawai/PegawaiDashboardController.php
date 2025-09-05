<?php

namespace App\Http\Controllers\Pegawai;


use File;
use Carbon\Carbon;
use App\Models\Lokasi;
use App\Models\Pegawai;
use App\Models\Presensi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PegawaiDashboardController extends Controller
{

    public function index()
    {
        $idPegawai = Auth::user()->id_pegawai;
        $pegawai = Pegawai::find($idPegawai);
        $lokasi = Lokasi::find($pegawai->id_lokasi);
        $today = Carbon::today(); //library carbon utk mengambil tanggal dan waktu 

        $timezone = match ($lokasi->zona_waktu) {
            'wib' => 'Asia/Jakarta',
            'wita' => 'Asia/Makassar',
            'wit' => 'Asia/Jayapura',
            default => config('app.timezone'),
        }; 

        $waktu_sekarang = Carbon::now($timezone)->format('H:i:s');

        $ambil_presensi_masuk = Presensi::where('id_pegawai', $idPegawai)
        ->whereDate('tanggal_masuk', $today)
        ->first();

        $data = [
            'lokasi_presensi' => $lokasi,
            'waktu_sekarang' => $waktu_sekarang,
            'zona_waktu' => $timezone,
            'ambil_presensi_masuk' => $ambil_presensi_masuk
        ];

        return view('pegawai.dashboard', $data);
    }


    public function presensi_masuk(Request $request)
    {
        // Validasi input terlebih dahulu
        $request->validate([
            'latitude_pegawai' => 'required|numeric',
            'longitude_pegawai' => 'required|numeric',
            'latitude_kantor' => 'required|numeric',
            'longitude_kantor' => 'required|numeric',
            'radius' => 'required|numeric',
            'jam_masuk' => 'required|date_format:H:i:s'
        ]);

        $idPegawai = Auth::user()->id_pegawai;
        $pegawai = Pegawai::find($idPegawai);

        if (!$pegawai) {
            return back()->with('alert-type', 'error')->with('alert-message', 'Data pegawai tidak ditemukan.');
        }

        $lokasi = Lokasi::find($pegawai->id_lokasi);
        if (!$lokasi) {
            return back()->with('alert-type', 'error')->with('alert-message', 'Data lokasi tidak ditemukan.');
        }

        // Cek apakah sudah presensi hari ini
        $today = Carbon::today();
        $cek = Presensi::where('id_pegawai', $idPegawai)
            ->whereDate('tanggal_masuk', $today)
            ->first();

        if ($cek) {
            return back()->with('alert-type', 'warning')->with('alert-message', 'Anda Sudah Melakukan Presensi Hari Ini!');
        }

        $jamMulaiKantor = Carbon::createFromFormat('H:i:s', $lokasi->jam_masuk);
        $jamMasukPegawai = Carbon::createFromFormat('H:i:s', $request->jam_masuk);

        $keterlambatanMenit = $jamMasukPegawai->greaterThan($jamMulaiKantor)
            ? $jamMulaiKantor->diffInMinutes($jamMasukPegawai)
            : 0;

        $lat_pegawai = (float) $request->latitude_pegawai;
        $lon_pegawai = (float) $request->longitude_pegawai;
        $lat_kantor = (float) $request->latitude_kantor;
        $lon_kantor = (float) $request->longitude_kantor;
        $radius = (float) $request->radius;

        if ($lat_pegawai === null || $lon_pegawai === null) {
            return back()->with('alert-type', 'error')->with('alert-message', 'Presensi Gagal. Lokasi Anda Tidak Ditemukan.');
        }

        // Perhitungan jarak menggunakan Haversine
        $lat_diff = deg2rad($lat_kantor - $lat_pegawai);
        $lon_diff = deg2rad($lon_kantor - $lon_pegawai);
        $lat_pegawai_rad = deg2rad($lat_pegawai);
        $lat_kantor_rad = deg2rad($lat_kantor);

        $a = sin($lat_diff / 2) ** 2 +
            cos($lat_pegawai_rad) * cos($lat_kantor_rad) *
            sin($lon_diff / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $jarak_meter = 6371 * $c * 1000;

        if ($jarak_meter > $radius) {
            return back()->with('alert-type', 'error')->with('alert-message', 'Presensi Gagal. Anda Berada Diluar Radius Kantor');
        }

        return view('pegawai.ambil_foto', [
            'id_pegawai' => $idPegawai,
            'tanggal_masuk' => Carbon::now()->toDateString(),
            'jam_masuk' => $request->jam_masuk,
            'terlambat' => $keterlambatanMenit
        ]);
    }



    public function presensi_masuk_aksi(Request $request)
    {
        $id = $request->input('id_pegawai'); //mengambil data dari form dengan method POST
        $tanggal_masuk = $request->input('tanggal_masuk');
        $jam_masuk = $request->input('jam_masuk');
        $foto_masuk = $request->input('foto_masuk');

        if ($foto_masuk) {
            // Hapus prefix dan decode base64
            $foto_masuk = str_replace('data:image/jpeg;base64,', '', $foto_masuk);
            $foto_masuk = str_replace(' ', '+', $foto_masuk);
            $imageData = base64_decode($foto_masuk);

            // Simpan file
            $namaFile = 'foto_masuk_' . Str::uuid() . '.jpg';
            Storage::disk('public')->put("foto_masuk/{$namaFile}", $imageData);

            \App\Models\Presensi::create([
                'id_pegawai' => $id,
                'tanggal_masuk' => $tanggal_masuk,
                'jam_masuk' => $jam_masuk,
                'foto_masuk' => $namaFile,
                'terlambat' => $request->input('terlambat'),
            ]);
            
            return redirect()->route('pegawai.dashboard')->with('alert-type', 'success')->with('alert-message', 'Presensi Masuk Berhasil Disimpan!');
        }

        return redirect()->back()->with('alert-type', 'error')->with('error', 'Gagal mengambil foto. Silakan coba lagi.');
    }


    public function presensi_keluar(Request $request, string $id)
    {
        $request->validate([
            'latitude_pegawai' => 'required|numeric',
            'longitude_pegawai' => 'required|numeric',
            'latitude_kantor' => 'required|numeric', 
            'longitude_kantor' => 'required|numeric',
            'radius' => 'required|numeric',
            'jam_keluar' => 'required'
        ]);

        $lat_pegawai = (float) $request->input('latitude_pegawai');
        $lon_pegawai = (float) $request->input('longitude_pegawai');
        $lat_kantor = (float) $request->input('latitude_kantor');
        $lon_kantor = (float) $request->input('longitude_kantor');
        $radius = (float) $request->input('radius');


        $lat_diff = deg2rad($lat_kantor - $lat_pegawai);
        $lon_diff = deg2rad($lon_kantor - $lon_pegawai);
        $lat_pegawai_rad = deg2rad($lat_pegawai);
        $lat_kantor_rad = deg2rad($lat_kantor);

        $a = sin($lat_diff / 2) ** 2 +
            cos($lat_pegawai_rad) * cos($lat_kantor_rad) *
            sin($lon_diff / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $jarak_meter = 6371 * $c * 1000;


        if ($lat_pegawai === null || $lon_pegawai === null) {
            return back()->with('alert-type', 'error')->with('alert-message', 'Presensi Gagal. Lokasi Anda Tidak Ditemukan.');
        }

    
        if ($jarak_meter > $radius) {
            return back()->with('alert-type', 'error')->with('alert-message', 'Presensi Gagal. Anda Berada Diluar Radius Kantor');
        }

        $presensi = Presensi::find($id);

        if (!$presensi) {
            return back()->with('alert-type', 'error')->with('alert-message', 'Data Presensi Tidak Ditemukan.');
        }

        if ($presensi->jam_keluar !== null) {
            return back()->with('alert-type', 'warning')->with('alert-message', 'Anda Sudah Melakukan Presensi Keluar.');
        }

        $jamMasuk = Carbon::createFromFormat('H:i:s', $presensi->jam_masuk);
        $jamKeluar = Carbon::createFromFormat('H:i:s', $request->input('jam_keluar'));

        // Cek validitas jam keluar
        if ($jamKeluar->lessThan($jamMasuk)) {
            return back()->with('alert-type', 'error')->with('alert-message', 'Jam keluar tidak boleh lebih awal dari jam masuk.');
        }

        // Hitung total jam kerja
        $durasi = $jamMasuk->diff($jamKeluar);
        $totalJamKerja = $durasi->h . ' jam ' . $durasi->i . ' menit';

        return view('pegawai.ambil_foto_keluar', [
            'id_presensi' => $id,
            'tanggal_keluar' => Carbon::now()->toDateString(),
            'jam_keluar' => $jamKeluar->format('H:i:s'),
            'total_jam' => $totalJamKerja
        ]);

    }


    public function presensi_keluar_aksi(Request $request, $id_presensi)
    {
        $foto_keluar = $request->input('foto_keluar');
        $tanggal_keluar = $request->input('tanggal_keluar');
        $jam_keluar = $request->input('jam_keluar');
        $total_jam = $request->input('total_jam');

        // Validasi data
        if (!$foto_keluar || !$tanggal_keluar || !$jam_keluar || !$total_jam) {
            return response()->json(['message' => 'Data tidak lengkap'], 400);
        }

        $presensi = Presensi::findOrFail($id_presensi);

        if ($foto_keluar) {
            // Decode dan simpan gambar
            $foto_keluar = str_replace('data:image/jpeg;base64,', '', $foto_keluar);
            $foto_keluar = str_replace(' ', '+', $foto_keluar);
            $imageData = base64_decode($foto_keluar);

            // Nama file
            $namaFile = 'foto_keluar_' . Str::uuid() . '.jpg';

            // Simpan file ke storage/public/foto_keluar/
            Storage::disk('public')->put("foto_keluar/{$namaFile}", $imageData);

            // Simpan data ke database
            $presensi->tanggal_keluar = $tanggal_keluar;
            $presensi->jam_keluar = $jam_keluar;
            $presensi->total_jam = $total_jam;
            $presensi->foto_keluar = $namaFile; 
            $presensi->save(); 
        }

        return redirect()->route('pegawai.dashboard')->with('alert-type', 'success')
            ->with('alert-message', 'Presensi Keluar Berhasil Disimpan!');
    }










}
                                        