
@extends('admin.layout')

@section('content')
<div class="card">
    <h5 class="card-header fw-bold">Kelola Admin</h5>
    <div class="card-body">

        <a href="{{ route('kelola_admin.create') }}" class="btn btn-secondary mb-3">Tambah Admin</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Id User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
                <tbody>
                @foreach ($admin as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->role }}</td>
                        <td style="display: flex; gap: 5px; align-items: center;">
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

        {{ $admin->links() }}
    </div>
</div>
@endsection


