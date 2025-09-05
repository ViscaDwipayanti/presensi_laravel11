@extends('admin.layout')

@section('content')
<div class="card">
    <h5 class="card-header fw-bold">Data Jabatan</h5>
    <div class="card-body">

        <a href="{{ route('jabatan.create') }}" class="btn btn-secondary mb-3">Tambah Jabatan</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Jabatan</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jabatans as $jabatan)
                <tr>
                    <td>{{ $loop->iteration + ($jabatans->currentPage() - 1) * $jabatans->perPage() }}</td>
                    <td>{{ $jabatan->nama_jabatan }}</td>
                    <td>
                        {{ $jabatan->created_at ? $jabatan->created_at->format('d M Y') : '-' }}
                    </td>
                    <td>
                        <a href="{{ route('jabatan.edit', $jabatan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('jabatan.destroy', $jabatan->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus jabatan ini?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Data jabatan tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $jabatans->links() }}
    </div>
</div>
@endsection
