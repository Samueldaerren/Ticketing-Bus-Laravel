@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center mb-4">Pesan Tiket: {{ $ticket->bus_name }} ({{ $ticket->bus_number }})</h2>
    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-body">
            <p><strong>Rute:</strong> {{ $ticket->origin }} → {{ $ticket->destination }}</p>
            <p><strong>Berangkat:</strong> {{ $ticket->departure_date }}</p>
            <p><strong>Harga per Kursi:</strong> Rp{{ number_format($ticket->price, 0, ',', '.') }}</p>
            <p><strong>Kursi Tersedia:</strong> {{ $availableSeats }}</p>

            <form action="{{ route('tickets.order.store', $ticket->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="jumlah_kursi" class="form-label">Jumlah Kursi</label>
                    <input type="number" name="jumlah_kursi" id="jumlah_kursi" class="form-control" min="1" max="{{ $availableSeats }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Total Harga</label>
                    <input type="text" id="totalPrice" class="form-control" readonly>
                </div>

                <button type="submit" class="btn btn-success w-100">Pesan Sekarang</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const jumlahKursiInput = document.getElementById('jumlah_kursi');
    const totalPriceInput = document.getElementById('totalPrice');
    const hargaPerKursi = {{ $ticket->price }};

    jumlahKursiInput.addEventListener('input', function () {
        let jumlahKursi = parseInt(this.value) || 0;
        let totalHarga = hargaPerKursi * jumlahKursi;
        totalPriceInput.value = "Rp" + new Intl.NumberFormat('id-ID').format(totalHarga);
    });
});
</script>
@endsection
