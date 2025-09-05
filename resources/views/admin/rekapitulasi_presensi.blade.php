@extends('admin.layout')

@section('content')
<div class="card">
    <h5 class="card-header fw-bold">Rekapitulasi Presensi Pegawai</h5>
    <div class="card-body">

        {{-- Filter Data --}}
<form action="{{ route('rekapitulasi.presensi') }}" method="GET" class="mb-4 row g-3 align-items-end">
    <div class="col-md-2">
        <label for="bulan" class="form-label">Bulan</label>
        <select name="bulan" id="bulan" class="form-select" required>
            @foreach(range(1,12) as $bln)
                <option value="{{ $bln }}" {{ request('bulan', date('m')) == $bln ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($bln)->format('F') }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <label for="tahun" class="form-label">Tahun</label>
        <select name="tahun" id="tahun" class="form-select" required>
            @foreach(range(date('Y'), date('Y') - 5) as $thn)
                <option value="{{ $thn }}" {{ request('tahun', date('Y')) == $thn ? 'selected' : '' }}>
                    {{ $thn }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <label for="pegawai" class="form-label">Pegawai</label>
        <select name="pegawai" id="pegawai" class="form-select">
            <option value="">-- Semua Pegawai --</option>
            @foreach($pegawais as $pegawai)
                <option value="{{ $pegawai->id }}" {{ request('pegawai') == $pegawai->id ? 'selected' : '' }}>
                    {{ $pegawai->nama_pegawai }} ({{ $pegawai->nip }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label for="jabatan" class="form-label">Jabatan</label>
        <select name="jabatan" id="jabatan" class="form-select">
            <option value="">-- Semua Jabatan --</option>
            @foreach($jabatans as $jabatan)
                <option value="{{ $jabatan->id }}" {{ request('jabatan') == $jabatan->id ? 'selected' : '' }}>
                    {{ $jabatan->nama_jabatan }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label for="lokasi" class="form-label">Lokasi</label>
        <select name="lokasi" id="lokasi" class="form-select">
            <option value="">-- Semua Lokasi --</option>
            @foreach($lokasis as $lokasi)
                <option value="{{ $lokasi->id }}" {{ request('lokasi') == $lokasi->id ? 'selected' : '' }}>
                    {{ $lokasi->nama_lokasi }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-12 mt-3 d-flex justify-content-between">
        <div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('rekapitulasi.presensi') }}" class="btn btn-secondary">Reset</a>
        </div>
        <div>
            <a href="{{ route('rekap.presensi.pdf', request()->query()) }}" target="_blank" class="btn btn-danger">
                <i class="bi bi-printer"></i> Cetak PDF
            </a>
        </div>
    </div>
</form>


        {{-- Tabel Rekapitulasi --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th class="text-start">Nama Pegawai</th>
                        <th>Jabatan</th>
                        <th>Hadir</th>
                        <th>Terlambat</th>
                        <th>Izin</th>
                        <th>Sakit</th>
                        <th>Alpha</th>
                        <th>Total Hari Kerja</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rekapitulasi as $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-start">
                            {{ $data->pegawai->nama_pegawai }}<br>
                            <small class="text-muted">{{ $data->pegawai->nip }}</small>
                        </td>
                        <td>{{ $data->pegawai->jabatan->nama_jabatan ?? '-' }}</td>
                        <td class="{{ $data->hadir == 0 ? 'text-danger' : '' }}">{{ $data->hadir }}</td>
                        <td class="{{ $data->terlambat > 0 ? 'text-warning' : '' }}">{{ $data->terlambat }}</td>
                        <td>{{ $data->izin }}</td>
                        <td>{{ $data->sakit }}</td>
                        <td class="{{ $data->alpha > 0 ? 'text-danger fw-bold' : '' }}">
                            {{ $data->alpha > 0 ? $data->alpha : '-' }}
                        </td>

                        <td>{{ $data->total_hari_kerja }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Data rekap tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection