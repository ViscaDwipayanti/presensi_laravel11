@extends('admin.layout')

@section('content')
    <div class="card">
        <h5 class="card-header fw-bold">Data Ketidakhadiran Pegawai</h5>
        <div class="card-body">
            <form action="{{ route('admin.ketidakhadiran.exportPdf') }}" method="GET" target="_blank" class="mb-3">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label for="tanggal_awal" class="form-label">Filter Tanggal</label>
                        <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" autocomplete="off" required>
                    </div>
                    <div class="col-md-4">
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" autocomplete="off" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-danger">Export PDF</button>
                    </div>
                </div>
            </form>


            {{-- <a href="{{ route('admin.ketidakhadiran.exportPdf') }}" class="btn btn-danger mb-3" target="_blank">Export Pdf</a> --}}
            
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pegawai</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Keterangan</th>
                        <th>Lampiran</th>
                        <th>Status</th>
                        <th>Aksi</th> {{-- Tambah kolom aksi --}}
                    </tr>
                </thead>
                <tbody>
                   @if ($ketidakhadiran->count())
                    @foreach ($ketidakhadiran as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->user->pegawai->nama_pegawai ?? '-' }}</td>
                            <td>{{ $item->tanggal_mulai }}</td>
                            <td>{{ $item->tanggal_selesai }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>
                                @if ($item->lampiran)
                                    <a href="{{ asset('storage/' . $item->lampiran) }}" target="_blank" class="btn btn-primary btn-sm">
                                        Lampiran
                                    </a>
                                @else
                                    <button class="btn btn-secondary btn-sm" disabled>Tidak ada</button>
                                @endif
                            </td>
                            <td>{{ ucfirst($item->status) }}</td>
                            <td style="display: flex; gap: 5px;">
                                @if ($item->status ==='pending')
                                <form id="form-setujui-{{ $item->id }}" action="{{ route('admin.ketidakhadiran.setujui', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="button" class="btn btn-success btn-sm btn-setujui" data-id="{{ $item->id }}">Setujui</button>
                                </form>
                                @else
                                    <button type="button" class="btn btn-success btn-sm" disabled>Setujui</button>
                                @endif

                                @if ($item->status ==='pending')
                                <button type="button"
                                    class="btn btn-danger btn-sm"
                                    data-id="{{ $item->id }}"
                                    data-action="{{ route('admin.ketidakhadiran.tolak', $item->id) }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalTolak">
                                    Tolak
                                </button>
                                @else
                                    <button type="button" class="btn btn-danger btn-sm" disabled>Tolak</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="text-center">Data tidak ditemukan</td>
                    </tr>
                @endif
                </tbody>
            </table> 

            {{ $ketidakhadiran->links() }}
        </div>
    </div>


<!-- Modal Tolak -->
<div class="modal fade" id="modalTolak" tabindex="-1" aria-labelledby="modalTolakLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formTolak" method="POST">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Pengajuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="alasan_penolakan" class="form-label">Alasan</label>
                    <textarea class="form-control" name="alasan_penolakan" id="alasan_penolakan" rows="3" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger btn-tolak">Tolak</button>
            </div>
        </div>
    </form>
  </div>
</div>

<script>
    const modalTolak = document.getElementById('modalTolak');
    const formTolak = document.getElementById('formTolak');

    modalTolak.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const action = button.getAttribute('data-action');

        formTolak.action = action;
        document.getElementById('alasan_penolakan').value = ''; // Reset textarea
    });
</script>




{{-- Switch Alert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Setujui
        document.querySelectorAll('.btn-setujui').forEach(function (button) {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                Swal.fire({
                    title: 'Yakin menyetujui pengajuan ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Setujui',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-setujui-' + id).submit();
                    }
                });
            });
        });

        // Tolak
        document.querySelectorAll('.btn-tolak').forEach(function (button) {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                Swal.fire({
                    title: 'Yakin menolak pengajuan ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Tolak',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-tolak-' + id).submit();
                    }
                });
            });
        });
    });
</script>

@endsection
