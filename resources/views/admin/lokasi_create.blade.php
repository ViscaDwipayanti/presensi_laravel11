@extends('admin.layout')

@section('content')
    <div class="card">
        <h5 class="card-header">Tambah Data Lokasi</h5>
        <div class="card col-md-8">
            <div class="card-body">
                <form action="{{ route('lokasi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf {{-- untuk keamanan --}}

                    <div class="form-group mt-1 mb-3">
                        <label for="kode_lokasi">Kode Lokasi</label>
                        <input type="text" class="form-control @error('kode_lokasi') is-invalid @enderror" 
                               id="kode_lokasi" name="kode_lokasi" 
                               value="{{ $kodeLokasiBaru }}" readonly> 
                        <span class="text-danger">{{ $errors->first('kode_lokasi') }}</span>
                    </div>

                    <div class="form-group mt-1 mb-3">
                        <label for="nama_lokasi">Nama Lokasi</label>
                        <input type="text" class="form-control @error('nama_lokasi') is-invalid @enderror" id="nama_lokasi" 
                               name="nama_lokasi" value="{{ old('nama_lokasi') }}"> 
                        <span class="text-danger">{{ $errors->first('nama_lokasi') }}</span> 
                    </div>

                    <div class="form-group mt-1 mb-3">
                        <label for="alamat">Alamat</label>
                        <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" 
                               name="alamat" value="{{ old('alamat') }}"> 
                        <span class="text-danger">{{ $errors->first('alamat') }}</span> 
                    </div>

                    <div class="form-group mt-1 mb-3">
                        <label for="latitude">Latitude</label>
                        <input type="text" class="form-control @error('latitude') is-invalid @enderror" id="latitude" 
                               name="latitude" value="{{ old('latitude') }}"> 
                        <span class="text-danger">{{ $errors->first('latitude') }}</span> 
                    </div>

                    <div class="form-group mt-1 mb-3">
                        <label for="longitude">Longitude</label>
                        <input type="text" class="form-control @error('longitude') is-invalid @enderror" id="longitude" 
                               name="longitude" value="{{ old('longitude') }}"> 
                        <span class="text-danger">{{ $errors->first('longitude') }}</span> 
                    </div>

                    <div class="form-group mt-1 mb-3">
                        <label for="radius">Radius</label>
                        <input type="number" class="form-control @error('radius') is-invalid @enderror" id="radius" 
                               name="radius" value="{{ old('radius') }}"> 
                        <span class="text-danger">{{ $errors->first('radius') }}</span> 
                    </div>

                    <div class="form-group mt-1 mt-3">
                        <label for="zona_waktu">Zona Waktu</label>
                        <select name="zona_waktu" class="form-control">
                            <option value="">-- Pilih Zona Waktu --</option>
                            <option value="wib" @selected(old('zona_waktu') == 'wib')>WIB</option>
                            <option value="wita" @selected(old('zona_waktu') == 'wita')>WITA</option>
                            <option value="wit" @selected(old('zona_waktu') == 'wit')>WIT</option>
                        </select>
                        <span class="text-danger">{{ $errors->first('zona_waktu') }}</span>
                    </div>

                    <div class="form-group mt-3 mb-3">
                        <label for="jam_masuk">Jam Masuk</label>
                        <input type="time" class="form-control @error('jam_masuk') is-invalid @enderror" id="jam_masuk" 
                               name="jam_masuk" value="{{ old('jam_masuk') }}"> 
                        <span class="text-danger">{{ $errors->first('jam_masuk') }}</span> 
                    </div>

                    <div class="form-group mt-1 mb-3">
                        <label for="jam_keluar">Jam Keluar</label>
                        <input type="time" class="form-control @error('jam_keluar') is-invalid @enderror" id="jam_keluar" 
                               name="jam_keluar" value="{{ old('jam_keluar') }}"> 
                        <span class="text-danger">{{ $errors->first('jam_keluar') }}</span> 
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('lokasi.index') }}" class="btn btn-danger">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
