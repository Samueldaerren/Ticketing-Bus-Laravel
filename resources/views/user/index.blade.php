@extends('layouts.app')

@section('content')
<div class="container">

@if(session('error'))
    <div class="alert alert-danger text-center">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success text-center">
        {{ session('success') }}
    </div>
@endif

<h2 class="text-center mb-4">Daftar Tiket</h2>

<!-- Input Search -->
<div class="mb-3">
    <input type="text" id="searchTicket" class="form-control" placeholder="Cari tiket berdasarkan bus, rute, atau harga...">
</div>

<div class="row" id="ticketContainer">
    @foreach($tickets as $ticket)
        @php
            $totalBooked = $ticket->bookings->whereIn('status', ['pending', 'paid'])->sum('jumlah_kursi');
            $availableSeats = max(0, $ticket->capacity - $totalBooked);
        @endphp
        <div class="col-md-4 mb-4 ticket-card" 
            data-bus="{{ strtolower($ticket->bus_name) }}" 
            data-route="{{ strtolower($ticket->origin) }} {{ strtolower($ticket->destination) }}" 
            data-price="{{ $ticket->price }}">
            <div class="card">
                <div class="card-body">
                    <img src="{{ $ticket->image ? asset('storage/' . $ticket->image) : asset('images/default-ticket.jpg') }}" 
                        class="card-img-top" alt="Gambar Tiket" width="220" height="220" style="object-fit: cover; border-radius: 5px;">
                    <h5 class="card-title mt-3">{{ $ticket->bus_name }} ({{ $ticket->bus_number }})</h5>
                    <p><strong>Rute:</strong> {{ $ticket->origin }} → {{ $ticket->destination }}</p>
                    <p><strong>Berangkat:</strong> {{ $ticket->departure_date }}</p>
                    <p><strong>Harga:</strong> Rp{{ number_format($ticket->price, 0, ',', '.') }}</p>
                    <p><strong>Kursi Tersedia:</strong> {{ $availableSeats }}</p>

                    @if($availableSeats > 0)
                        <a href="{{ route('tickets.order', $ticket->id) }}" class="btn btn-primary w-100">Pesan Sekarang</a>
                    @else
                        <button class="btn btn-danger w-100" disabled>Sold Out</button>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
</div>

<script>
document.getElementById('searchTicket').addEventListener('keyup', function() {
    let keyword = this.value.toLowerCase();
    let tickets = document.querySelectorAll('.ticket-card');

    tickets.forEach(function(ticket) {
        let busName = ticket.getAttribute('data-bus');
        let route = ticket.getAttribute('data-route');
        let price = ticket.getAttribute('data-price');

        if (busName.includes(keyword) || route.includes(keyword) || price.includes(keyword)) {
            ticket.style.display = "block";
        } else {
            ticket.style.display = "none";
        }
    });
});
</script>

@endsection
