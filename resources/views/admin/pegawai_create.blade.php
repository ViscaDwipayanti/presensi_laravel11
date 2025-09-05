@extends('admin.layout')

@section('content')
<div class="card">
    <h5 class="card-header fw-bold">Tambah Pegawai</h5>
    <div class="card-body">
        <form action="{{ route('pegawai.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="nip" class="form-label">NIP (Otomatis)</label>
                <input type="text" name="nip" class="form-control" value="{{ $nipBaru }}" readonly>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="nama_pegawai" class="form-label">Nama Pegawai</label>
                <input type="text" name="nama_pegawai" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    {{-- <option value="admin">Admin</option> --}}
                    <option value="pegawai">Pegawai</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="no_hp" class="form-label">No HP</label>
                <input type="text" name="no_hp" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="id_jabatan" class="form-label">Jabatan</label>
                <select name="id_jabatan" class="form-control" required>
                    <option value="">-- Pilih Jabatan --</option>
                    @foreach($jabatans as $jabatan)
                        <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="id_lokasi" class="form-label">Lokasi</label>
                <select name="id_lokasi" class="form-control" required>
                    <option value="">-- Pilih Lokasi --</option>
                    @foreach($lokasis as $lokasi)
                        <option value="{{ $lokasi->id }}">{{ $lokasi->kode_lokasi }} -{{ $lokasi->nama_lokasi }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="foto" class="form-label">Foto</label>
                <input type="file" name="foto" class="form-control" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('pegawai.index') }}" class="btn btn-danger">Batal</a>
        </form>
    </div>
</div>
@endsection
