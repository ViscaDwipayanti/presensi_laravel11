@extends('pegawai.layout')
@section('content')
<div class="card">
    <h5 class="card-header fw-bold">Rekap Presensi</h5>

    <form method="GET" action="{{ route('rekap_presensi_pegawai.index') }}" class="row g-3 p-3 align-items-end">
        <div class="col-md-3">
            <label for="tanggal_awal" class="form-label">Filter Tanggal</label>
            <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" autocomplete="off" value="{{ request('tanggal_awal') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" autocomplete="off" value="{{ request('tanggal_akhir') }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary mt-4">Cari</button>
        </div>
        <div class="col-auto">
            <a href="{{ route('rekap_presensi_pegawai.exportPdf', ['tanggal_awal' => request('tanggal_awal'), 'tanggal_akhir' => request('tanggal_akhir')]) }}" class="btn btn-danger mt-4">
                Export PDF
            </a>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Pegawai</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Total Jam</th>
                <th>Terlambat</th>
            </tr>
        </thead>
<tbody>
                @forelse($models as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->pegawai->nama_pegawai ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d F Y') }}</td>
                        <td>{{ $item->jam_masuk }}</td>
                        <td>{{ $item->jam_keluar ?? '-' }}</td>
                        <td>{{ $item->total_jam ?? '-' }}</td>
                        <td>
                            @if ($item->terlambat > 0)
                                {{ floor($item->terlambat / 60) }} jam {{ $item->terlambat % 60 }} menit
                            @else
                                On Time
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data presensi</td>
                    </tr>
                @endforelse
            </tbody>
    </table>
</div>
@endsection
