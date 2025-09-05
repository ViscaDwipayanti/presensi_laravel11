@extends('admin.layout')

@section('content')
<style>
    .dashboard-card {
        min-height: 130px;
        display: flex;
        align-items: center;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h5 class="card-title fw-semibold mb-4 fs-6">Dashboard</h5>

                    {{-- Card Dashboard --}}
                    <div class="row g-3 align-items-stretch">
                        {{-- Total Pegawai --}}
                        <div class="col-md-3">
                            <div class="card h-100 shadow-sm rounded dashboard-card">
                                <div class="card-body d-flex align-items-center">
                                    <i class="fa-solid fa-users fa-3x text-primary me-3"></i>
                                    <div>
                                        <h4 class="mb-1 fs-5">Total Pegawai</h4>
                                        <p class="mb-0 text-muted fs-5">{{ $total_pegawai }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Hadir --}}
                        <div class="col-md-3">
                            <div class="card h-100 shadow-sm rounded dashboard-card">
                                <div class="card-body d-flex align-items-center">
                                    <i class="fa-solid fa-user-check fa-3x text-success me-3"></i>
                                    <div>
                                        <h4 class="mb-1 fs-5">Hadir</h4>
                                        <p class="mb-0 text-muted fs-5">{{ $presensi_perhari }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Alpa --}}
                        <div class="col-md-3">
                            <div class="card h-100 shadow-sm rounded dashboard-card">
                                <div class="card-body d-flex align-items-center">
                                    <i class="fa-solid fa-user-xmark fa-3x text-danger me-3"></i>
                                    <div>
                                        <h4 class="mb-1 fs-5">Alpa</h4>
                                        <p class="mb-0 text-muted fs-5">{{ $alpa }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Cuti/Izin/Sakit --}}
                        <div class="col-md-3">
                            <div class="card h-100 shadow-sm rounded dashboard-card">
                                <div class="card-body d-flex align-items-center">
                                    <i class="fa-solid fa-user-clock fa-3x text-warning me-3"></i>
                                    <div>
                                        <h4 class="mb-1 fs-5">Cuti/Izin/Sakit</h4>
                                        <p class="mb-0 text-muted fs-5">{{ $ketidakhadiran_perhari }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /.row -->

                </div>
            </div>

            <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-3 fs-6">Daftar Pegawai Tanpa Keterangan</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Pegawai</th>
                                <th>NIP</th>
                                <th>Jabatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pegawai_alpa as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama_pegawai }}</td>
                                    <td>{{ $item->nip }}</td>
                                    <td>{{ $item->nama_jabatan ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Semua pegawai telah melakukan presensi hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
</div>

        </div>
    </div>
</div>

{{-- CDN Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


@endsection
