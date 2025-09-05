@extends('admin.layout')

@section('content')
<div class="card">
    <h5 class="card-header fw-bold">Edit Data Pegawai</h5>
    <div class="card-body">
        <form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nip" class="form-label">NIP</label>
                <input type="text" name="nip" class="form-control" value="{{ $pegawai->nip }}" readonly>
            </div>

            <div class="mb-3">
                <label for="nama_pegawai" class="form-label">Nama Pegawai</label>
                <input type="text" name="nama_pegawai" class="form-control" value="{{ $pegawai->nama_pegawai }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-control" required>
                    <option value="Laki-laki" {{ $pegawai->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ $pegawai->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" required>{{ $pegawai->alamat }}</textarea>
            </div>

            <div class="mb-3">
                <label for="no_hp" class="form-label">No HP</label>
                <input type="text" name="no_hp" class="form-control" value="{{ $pegawai->no_hp }}" required>
            </div>

            <div class="mb-3">
                <label for="id_jabatan" class="form-label">Jabatan</label>
                <select name="id_jabatan" class="form-control" required>
                    @foreach($jabatans as $jabatan)
                        <option value="{{ $jabatan->id }}" {{ $pegawai->id_jabatan == $jabatan->id ? 'selected' : '' }}>{{ $jabatan->nama_jabatan }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="id_lokasi" class="form-label">Lokasi</label>
                <select name="id_lokasi" class="form-control" required>
                    @foreach($lokasis as $lokasi)
                        <option value="{{ $lokasi->id }}" {{ $pegawai->id_lokasi == $lokasi->id ? 'selected' : '' }}>{{ $lokasi->nama_lokasi }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="foto" class="form-label">Foto</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
                @if($pegawai->foto)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $pegawai->foto) }}" width="100" alt="Foto Pegawai">
                    </div>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('pegawai.index') }}" class="btn btn-danger">Batal</a>
        </form>
    </div>
</div>
@endsection
