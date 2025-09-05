@extends('admin.layout')

@section('content')
<div class="card">
    <h5 class="card-header fw-bold">Tambah Data Jabatan</h5>
    <div class="card-body">

        <form action="{{ route('jabatan.store') }}" method="POST">
            @csrf

            <div class="form-group mt-1 mb-3">
                <label for="nama_jabatan">Nama Jabatan</label>
                <input type="text" class="form-control @error('nama_jabatan') is-invalid @enderror"
                    id="nama_jabatan" name="nama_jabatan" value="{{ old('nama_jabatan') }}" required autofocus>
                @error('nama_jabatan')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">simpan</button>
            <a href="{{ route('jabatan.index') }}" class="btn btn-danger">Batal</a>
        </form>

    </div>
</div>
@endsection
