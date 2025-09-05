@extends('pegawai.layout')

@section('content')
<div class="card">
    <h5 class="card-header fw-bold">Edit Pengajuan Ketidakhadiran</h5>
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

        <form action="{{ route('ketidakhadiran.update', $ketidakhadiran->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', $ketidakhadiran->tanggal_mulai) }}" required>
                    <small class="text-muted">Tanggal Ketidakhadiran</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai', $ketidakhadiran->tanggal_selesai) }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <select name="keterangan" class="form-control" required>
                    <option value="">-- Pilih Keterangan --</option>
                    <option value="Izin" {{ old('keterangan', $ketidakhadiran->keterangan) == 'Izin' ? 'selected' : '' }}>Izin</option>
                    <option value="Sakit" {{ old('keterangan', $ketidakhadiran->keterangan) == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="Cuti" {{ old('keterangan', $ketidakhadiran->keterangan) == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="lampiran" class="form-label">Lampiran</label>
                @if($ketidakhadiran->lampiran)
                    <p>
                        <a href="{{ asset('storage/' . $ketidakhadiran->lampiran) }}" target="_blank" class="btn btn-sm btn-info">Lihat Lampiran Saat Ini</a>
                    </p>
                @endif
                <input type="file" name="lampiran" class="form-control" accept=".pdf,image/*">
                <small class="text-muted">Format PDF atau gambar. Biarkan kosong jika tidak ingin mengubah.</small>
            </div>

            <button type="submit" class="btn btn-primary">Update Pengajuan</button>
            <a href="{{ route('ketidakhadiran.index') }}" class="btn btn-danger">Batal</a>
        </form>
    </div>
</div>
@endsection
