@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Tiket</h2>
    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="bus_number" class="form-label">Bus Number</label>
            <input type="text" name="bus_number" class="form-control" value="{{ old('bus_number', $ticket->bus_number) }}" required>
        </div>

        <div class="mb-3">
            <label for="bus_name" class="form-label">Bus Name</label>
            <input type="text" name="bus_name" class="form-control" value="{{ old('bus_name', $ticket->bus_name) }}" required>
        </div>

        <div class="mb-3">
            <label for="capacity" class="form-label">Capacity</label>
            <input type="number" name="capacity" class="form-control" value="{{ old('capacity', $ticket->capacity) }}" required>
        </div>

        <div class="mb-3">
            <label for="bus_type" class="form-label">Bus Type</label>
            <select name="bus_type" class="form-control" required>
                <option value="Ekonomi" {{ old('bus_type', $ticket->bus_type) == 'Ekonomi' ? 'selected' : '' }}>Ekonomi</option>
                <option value="Bisnis" {{ old('bus_type', $ticket->bus_type) == 'Bisnis' ? 'selected' : '' }}>Bisnis</option>
                <option value="Eksekutif" {{ old('bus_type', $ticket->bus_type) == 'Eksekutif' ? 'selected' : '' }}>Eksekutif</option>
                <option value="VIP" {{ old('bus_type', $ticket->bus_type) == 'VIP' ? 'selected' : '' }}>VIP</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="origin" class="form-label">Origin</label>
            <input type="text" name="origin" class="form-control" value="{{ old('origin', $ticket->origin) }}" required>
        </div>

        <div class="mb-3">
            <label for="destination" class="form-label">Destination</label>
            <input type="text" name="destination" class="form-control" value="{{ old('destination', $ticket->destination) }}" required>
        </div>

        <div class="mb-3">
            <label for="departure_date" class="form-label">Departure Date</label>
            <input type="date" name="departure_date" class="form-control" value="{{ old('departure_date', $ticket->departure_date) }}" required>
        </div>

        <div class="mb-3">
            <label for="arrival_date" class="form-label">Arrival Date</label>
            <input type="date" name="arrival_date" class="form-control" value="{{ old('arrival_date', $ticket->arrival_date) }}">
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Base Price</label>
            <input type="number" name="price" class="form-control" value="{{ old('price', $ticket->price - (['Ekonomi' => 0, 'Bisnis' => 50000, 'Eksekutif' => 100000, 'VIP' => 150000][$ticket->bus_type] ?? 0)) }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="active" {{ old('status', $ticket->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $ticket->status ?? 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Upload Gambar</label>
            <input type="file" class="form-control" name="image" accept="image/*">
        </div>

        <button type="submit" class="btn btn-warning">Update</button>
    </form>
</div>
@endsection
