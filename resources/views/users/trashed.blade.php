@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>User yang Dihapus</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('users.index') }}" class="btn btn-primary mb-3">Kembali</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <!-- Tombol Restore -->
                            <form action="{{ route('users.restore', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-warning btn-sm">Restore</button>
                            </form>

                            <!-- Tombol Hapus Permanen -->
                            <form action="{{ route('users.forceDelete', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus permanen? Data tidak bisa dikembalikan!');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus Permanen</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
