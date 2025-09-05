@extends('admin.layout')

@section('content')
    <div class="card">
        <h5 class="card-header">Tambah Data Admin</h5>
        <div class="card col-md-8">
            <div class="card-body">
                <form action="{{ route('kelola_admin.store') }}" method="POST">
                    @csrf 
                    <div class="form-group mt-1 mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" 
                               value="{{ old('email') }}"> 
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    </div>

                    <div class="form-group mt-1 mb-3">
                        <label for="password">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password"> 
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                    </div>

                    <div class="form-group mt-1 mb-3">
                        <label for="role">Role</label>
                        <select name="role" class="form-control @error('role') is-invalid @enderror" id="role">
                            <option value="">-- Pilih Role --</option>
                            <option value="admin" @selected(old('role') == 'admin')>Admin</option>
                        </select>
                        <span class="text-danger">{{ $errors->first('role') }}</span>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('kelola_admin.index') }}" class="btn btn-danger">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
