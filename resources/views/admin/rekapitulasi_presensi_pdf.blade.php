<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekapitulasi Presensi Pegawai</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #000;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        .info-container {
            width: 100%;
            margin-bottom: 15px;
            font-size: 11px;
        }

        .info-left, .info-right {
            width: 49%;
            display: inline-block;
            vertical-align: top;
        }

        .info-left {
            text-align: left;
        }

        .info-right {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        table th, table td {
            padding: 6px 8px;
            text-align: center;
            vertical-align: middle;
        }

        table th {
            background-color: #f2f2f2;
        }

        .name-cell {
            text-align: left;
        }

        .terlambat {
            color: #d58512; /* kuning/orange */
            font-weight: bold;
        }

        .alpha {
            color: #a94442; /* merah */
            font-weight: bold;
        }

        .fw-bold {
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
        }
    </style>
</head>
<body>

    <h2>Rekapitulasi Presensi Pegawai</h2>

    <div class="info-container">
        <div class="info-left">
            <p><strong>Tanggal Cetak:</strong> {{ now()->format('d-m-Y') }}</p>
            @if(auth()->check() && auth()->user()->pegawai)
                <p><strong>Dicetak oleh:</strong> {{ auth()->user()->pegawai->nama_pegawai }}</p>
            @endif
        </div>
        <div class="info-right">
            @if(request()->filled('bulan') && request()->filled('tahun'))
                <p><strong>Periode:</strong> {{ \Carbon\Carbon::create(request('tahun'), request('bulan'))->format('F Y') }}</p>
            @else
                <p><strong>Periode:</strong> Semua Data</p>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 25%;">Nama Pegawai</th>
                <th style="width: 15%;">Jabatan</th>
                <th style="width: 7%;">Hadir</th>
                <th style="width: 7%;">Terlambat</th>
                <th style="width: 7%;">Izin</th>
                <th style="width: 7%;">Sakit</th>
                <th style="width: 7%;">Alpha</th>
                <th style="width: 12%;">Total Hari Kerja</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rekapitulasi as $index => $data)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="name-cell">{{ $data->pegawai->nama_pegawai }}</td>
                <td>{{ $data->pegawai->jabatan->nama_jabatan ?? '-' }}</td>
                <td>{{ $data->hadir }}</td>
                <td class="{{ $data->terlambat > 0 ? 'terlambat' : '' }}">{{ $data->terlambat }}</td>
                <td>{{ $data->izin }}</td>
                <td>{{ $data->sakit }}</td>
                <td class="{{ $data->alpha > 0 ? 'alpha fw-bold' : '' }}">
                    {{ $data->alpha > 0 ? $data->alpha : '-' }}
                </td>
                <td>{{ $data->total_hari_kerja }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center;">Tidak ada data presensi ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Disetujui,</p>
        <br><br>
        <p><strong>_</strong></p>
        <p><em>(Tanda Tangan Admin)</em></p>
    </div>

</body>
</html>