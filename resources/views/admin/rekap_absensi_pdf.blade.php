
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Presensi Pegawai</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #000;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .info-container {
            width: 100%;
            margin-bottom: 10px;
        }

        .info-left, .info-right {
            width: 49%;
            display: inline-block;
            vertical-align: top;
            font-size: 11px;
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
            margin-top: 10px;
        }

        table th, table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
        }
    </style>
</head>
<body>

    <h2>Rekap Presensi Pegawai</h2>

    <div class="info-container">
        <div class="info-left">
            <p><strong>Tanggal Cetak:</strong> {{ now()->format('d-m-Y') }}</p>
            @if(auth()->check() && auth()->user()->pegawai)
                <p><strong>Dicetak oleh:</strong> {{ auth()->user()->pegawai->nama_pegawai }}</p>
            @endif
        </div>
        <div class="info-right">
            @if($tanggalAwal && $tanggalAkhir)
                <p><strong>Periode:</strong> {{ $tanggalAwal }} - {{ $tanggalAkhir }}</p>
            @else
                <p><strong>Periode:</strong> Semua Data</p>
            @endif
        </div>
    </div>

     <table>
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
            @foreach ($models as $index => $item)
                @php
                    $masuk = \Carbon\Carbon::parse($item->jam_masuk);
                    $keluar = \Carbon\Carbon::parse($item->jam_keluar);
                    $total = $keluar->diff($masuk);
                    $terlambat = $masuk->gt(\Carbon\Carbon::createFromTimeString('08:00:00')) 
                        ? $masuk->diff(\Carbon\Carbon::createFromTimeString('08:00:00')) 
                        : null;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->pegawai->nama_pegawai ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d-m-Y') }}</td>
                    <td>{{ $item->jam_masuk }}</td>
                    <td>{{ $item->jam_keluar }}</td>
                    <td>{{ $total->h }} Jam {{ $total->i }} Menit</td>
                    <td>{{ $terlambat ? $terlambat->h . ' Jam ' . $terlambat->i . ' Menit' : '0' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Disetujui,</p>
        <br><br>
        <p><strong>_______________________</strong></p>
        <p><em>(Tanda Tangan Admin)</em></p>
    </div>

</body>
</html>
