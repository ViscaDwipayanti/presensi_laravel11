@extends('pegawai.layout')

@section('content')
    <div class="card">
        <h5 class="card-header fw-bold">Ketidakhadiran</h5>
        <div class="card-body">

            <a href="{{ route('ketidakhadiran.create') }}" class="btn btn-secondary mb-3">Pengajuan Ketidakhadiran</a>

            
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Keterangan</th>
                        <th>Lampiran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($ketidakhadiran->count())
                        @foreach ($ketidakhadiran as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->tanggal_mulai }}</td>
                                <td>{{ $item->tanggal_selesai }}</td>
                                <td>{{ $item->keterangan }}</td>
                                <td>
                                    @if ($item->lampiran)
                                        <a href="{{ asset('storage/' . $item->lampiran) }}" target="_blank" class="btn btn-primary btn-sm">
                                            Lihat Lampiran
                                        </a>
                                    @else
                                        <button class="btn btn-secondary btn-sm" disabled>Tidak ada</button>
                                    @endif
                                </td>
                                <td>{{ ucfirst($item->status) }}</td>
                                <td>
                                    @if ($item->status === 'pending')
                                        <div style="display: flex; gap: 5px; align-items: center;">
                                            <a href="{{ route('ketidakhadiran.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('ketidakhadiran.destroy', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Anda Yakin?')">Delete</button>
                                            </form>
                                        </div>
                                    @elseif($item->status === 'disetujui')
                                        <i class="fa-solid fa-check fa-lg" style="color: #14d411;"></i>
                                    @elseif($item->status === 'ditolak')
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalDetail-{{ $item->id }}">
                                            Detail
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center">Data tidak ditemukan</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            {{-- Semua modal diletakkan di luar tabel --}}
            @foreach ($ketidakhadiran as $item)
                @if ($item->status === 'ditolak')
                    <div class="modal fade" id="modalDetail-{{ $item->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel-{{ $item->id }}">Detail Penolakan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Nama Pegawai</th>
                                            <td>{{ $item->user->pegawai->nama_pegawai ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal</th>
                                            <td>{{ $item->tanggal_mulai }} s/d {{ $item->tanggal_selesai }}</td>
                                        </tr>
                                        <tr>
                                            <th>Keterangan</th>
                                            <td>{{ $item->keterangan }}</td>
                                        </tr>
                                        <tr>
                                            <th>Alasan Penolakan</th>
                                            <td><p class="text-danger">{{ $item->alasan_penolakan }}</p></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach           
            {{ $ketidakhadiran->links() }}
        </div>
    </div>



@endsection
