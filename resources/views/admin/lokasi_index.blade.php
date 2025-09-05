@extends('admin.layout', ['title'=>'Data Lokasi Presensi'])
@section('content')
    <div class="card">
        <h5 class="card-header">Data Lokasi Presensi</h5>
        
        <div class="card-body">
        <a href="{{ route('lokasi.create') }}" class="btn btn-secondary mb-2">Tambah Lokasi</a>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Lokasi</th>
                    <th>Nama Lokasi</th>
                    {{-- <th>Alamat</th> --}}
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Radius</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($lokasi as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->kode_lokasi }}</td>
                        <td>{{ $item->nama_lokasi }}</td>
                        {{-- <td>{{ $item->alamat }}</td> --}}
                        <td>{{ $item->latitude }}</td>
                        <td>{{ $item->longitude }}</td>
                        <td>{{ $item->radius }}</td>
                        <td style="display: flex; gap: 5px; align-items: center;">
                            <a href="/admin/lokasi/{{ $item->id }}" class="btn btn-secondary btn-sm">Detail</a>
                            <a href="/admin/lokasi/{{ $item->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                            <form action="/admin/lokasi/{{ $item->id }}" method="POST" class="d-inline">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Anda Yakin?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {!! $lokasi->links() !!}
        </div>
    </div>
@endsection
