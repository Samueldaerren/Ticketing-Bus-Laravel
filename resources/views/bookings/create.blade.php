@extends('layouts.app')

@section('content')
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

    <h2>Tambah Booking</h2>
    <form action="{{ route('bookings.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="user_id" class="form-label">User</label>
            <select name="user_id" id="user_id" class="form-control">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-3">
            <label for="ticket_id" class="form-label">Tiket</label>
            <select name="ticket_id" id="ticket_id" class="form-control" required>
    <option value="">Pilih Tiket</option>
    @foreach($tickets as $ticket)
        @if($ticket->availableSeats > 0)
            <option value="{{ $ticket->id }}" data-available-seats="{{ $ticket->availableSeats }}">
                {{ $ticket->bus_number }} - {{ $ticket->origin }} → {{ $ticket->destination }} 
                (Sisa {{ $ticket->availableSeats }} kursi)
            </option>
        @else
            <option value="{{ $ticket->id }}" disabled>
                {{ $ticket->bus_number }} - {{ $ticket->origin }} → {{ $ticket->destination }} 
                (Tiket Habis)
            </option>
        @endif
    @endforeach
</select>

        </div>

        <div class="mb-3">
            <label for="jumlah_kursi" class="form-label">Jumlah Kursi</label>
            <input type="number" name="jumlah_kursi" class="form-control" required min="1">
            <small class="text-muted">Pilih tiket terlebih dahulu untuk melihat sisa kursi.</small>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="pending">Pending</option>
                <option value="paid">Paid</option>
                <option value="canceled">Canceled</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>

@endsection
