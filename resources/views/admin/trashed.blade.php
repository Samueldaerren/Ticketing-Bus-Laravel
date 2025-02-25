@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Booking yang Dihapus</h2>
    
    <a href="{{ route('admin-dashboard') }}" class="btn btn-primary mb-3">⬅ Kembali ke Dashboard</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama User</th>
                <th>Bus</th>
                <th>Jumlah Kursi</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->user->name }}</td>
                    <td>{{ $booking->ticket->bus_number }}</td>
                    <td>{{ $booking->jumlah_kursi }}</td>
                    <td>{{ $booking->total_harga }}</td>
                    <td>{{ $booking->status }}</td>
                    <td>
                    <div class="d-flex gap-2"> 
                        <form action="{{ route('bookings.restore', $booking->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-warning btn-sm">Restore</button>
                        </form>
 
                        <form action="{{ route('bookings.forceDelete', ['id' => $booking->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus permanen? Data tidak bisa dikembalikan!');">
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
