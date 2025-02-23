@extends('layouts.app')

@section('content')
<div class="container">
<div class="container">
    {{-- Notifikasi Error --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <h2>Edit Booking</h2>
    <form action="{{ route('bookings.update', $booking->id) }}" method="POST">
        @csrf
        @method('PUT')  <!-- HARUS ADA agar Laravel mengenali ini sebagai Update -->

        <div class="mb-3">
            <label for="user_id" class="form-label">User</label>
            <select name="user_id" id="user_id" class="form-control">
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $user->id == $booking->user_id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="ticket_id" class="form-label">Tiket</label>
            <select name="ticket_id" id="ticket_id" class="form-control">
                @foreach($tickets as $ticket)
                    <option value="{{ $ticket->id }}" {{ $ticket->id == $booking->ticket_id ? 'selected' : '' }}>
                        {{ $ticket->bus_number }} - {{ $ticket->origin }} → {{ $ticket->destination }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="jumlah_kursi" class="form-label">Jumlah Kursi</label>
            <input type="number" name="jumlah_kursi" class="form-control" value="{{ old('jumlah_kursi', $booking->jumlah_kursi) }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ $booking->status == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="canceled" {{ $booking->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
            </select>
        </div>

        <button type="submit" class="btn btn-warning">Update Booking</button>
    </form>
</div>
@endsection
