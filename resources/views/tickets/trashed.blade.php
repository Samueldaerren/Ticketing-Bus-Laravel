@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Deleted Tickets</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('tickets.index') }}" class="btn btn-secondary mb-3">Back to Tickets</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Bus Number</th>
                <th>Origin</th>
                <th>Destination</th>
                <th>Departure Date</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->bus_number }}</td>
                    <td>{{ $ticket->origin }}</td>
                    <td>{{ $ticket->destination }}</td>
                    <td>{{ $ticket->departure_date }}</td>
                    <td>{{ $ticket->price }}</td>
                    <td>{{ $ticket->status }}</td>
                    <td>
                        <form action="{{ route('tickets.restore', $ticket->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-warning btn-sm">Restore</button>
                        </form>
                        
                        <form action="{{ route('tickets.forceDelete', $ticket->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure? This action cannot be undone!');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete Permanently</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
