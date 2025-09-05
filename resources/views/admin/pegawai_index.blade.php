
@extends('admin.layout')

@section('content')
<div class="card">
    <h5 class="card-header fw-bold">Data Pegawai</h5>
    <div class="card-body">

        <a href="{{ route('pegawai.create') }}" class="btn btn-secondary mb-3">Tambah Pegawai</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama Pegawai</th>
                    <th>Jabatan</th>
                    <th>Lokasi</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pegawais as $pegawai)
                <tr>
                    <td>{{ $loop->iteration + ($pegawais->currentPage() - 1) * $pegawais->perPage() }}</td>
                    <td>{{ $pegawai->nip }}</td>
                    <td>{{ $pegawai->nama_pegawai }}</td>
                    <td>{{ $pegawai->jabatan->nama_jabatan }}</td>
                    <td>{{ $pegawai->lokasi->nama_lokasi }}</td>
                    <td>
                        @if($pegawai->foto && file_exists(storage_path('app/public/' . $pegawai->foto)))
                        <a href="{{ asset('storage/' . $pegawai->foto) }}" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('storage/' . $pegawai->foto) }}" width="50" height="50" alt="Foto">
                        </a>
                         @else
                        <span>-</span>
                         @endif
                    </td>

                    <td>
                        <a href="{{ route('pegawai.edit', $pegawai->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('pegawai.destroy', $pegawai->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Data pegawai tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $pegawais->links() }}
    </div>
</div>
@endsection


