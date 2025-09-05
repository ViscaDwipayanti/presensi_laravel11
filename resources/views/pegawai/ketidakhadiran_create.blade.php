@extends('pegawai.layout')

@section('content')
<div class="card">
    <h5 class="card-header fw-bold">Pengajuan Ketidakhadiran</h5>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('ketidakhadiran.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" required>
                    <small class="text-muted">Tanggal Ketidakhadiran</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <select name="keterangan" class="form-control" required>
                    <option value="">-- Pilih Keterangan --</option>
                    <option value="Izin">Izin</option>
                    <option value="Sakit">Sakit</option>
                    <option value="Cuti">Cuti</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="lampiran" class="form-label">Lampiran</label>
                <input type="file" name="lampiran" class="form-control" accept=".pdf,image/*">
                <small class="text-muted">Format PDF atau gambar.</small>
            </div>

            <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
            <a href="{{ route('ketidakhadiran.index') }}" class="btn btn-danger">Batal</a>
        </form>
    </div>
</div>
@endsection
