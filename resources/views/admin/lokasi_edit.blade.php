@extends('admin.layout')

@section('content')
<div class="card">
    <h5 class="card-header fw-bold">Edit Data Lokasi</h5>
    <div class="card-body">
    <form action="{{ route('lokasi.update', $lokasi->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama_lokasi" class="form-label">Nama Lokasi</label>
            <input type="text" name="nama_lokasi" class="form-control" value="{{ $lokasi->nama_lokasi }}" required>
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <input type="text" name="alamat" class="form-control" value="{{ $lokasi->alamat }}" required>
        </div>

        <div class="mb-3">
            <label for="latitude" class="form-label">Latitude</label>
            <input type="text" name="latitude" class="form-control" value="{{ $lokasi->latitude }}" required>
        </div>

        <div class="mb-3">
            <label for="longitude" class="form-label">Longitude</label>
            <input type="text" name="longitude" class="form-control" value="{{ $lokasi->longitude }}" required>
        </div>

        <div class="mb-3">
            <label for="radius" class="form-label">Radius</label>
            <input type="text" name="radius" class="form-control" value="{{ $lokasi->radius }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Zona Waktu</label>
            <select name="zona_waktu" class="form-control" required>
                <option value="wib" {{ $lokasi->zona_waktu == 'wib' ? 'selected' : '' }}>WIB</option>
                <option value="wita" {{ $lokasi->zona_waktu == 'wita' ? 'selected' : '' }}>WITA</option>
                <option value="wit" {{ $lokasi->zona_waktu == 'wit' ? 'selected' : '' }}>WIT</option>
            </select>
        </div>

        <div class="form-group mt-3 mb-3">
            <label for="jam_masuk">Jam Masuk</label>
            <input type="time" class="form-control" name="jam_masuk" value="{{ $lokasi->jam_masuk }}"> 
        </div>

        <div class="form-group mt-3 mb-3">
            <label for="jam_keluar">Jam Keluar</label>
            <input type="time" class="form-control" name="jam_keluar" value="{{ $lokasi->jam_keluar }}"> 
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('lokasi.index') }}" class="btn btn-danger">Batal</a>
    </form>
    </div>
</div>
@endsection
