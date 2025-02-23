@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detail Pemesanan</h2>
    <div class="row">
        <div class="col-md-6">
            <table class="table">
                <tr>
                    <th>Nomor Tiket</th>
                    <td>{{ $booking->id }}</td>
                </tr>
                <tr>
                    <th>Bus</th>
                    <td>{{ $booking->ticket->bus_name }} ({{ $booking->ticket->origin }} - {{ $booking->ticket->destination }})</td>
                </tr>
                <tr>
                    <th>Tipe Bus</th>
                    <td>{{ $booking->ticket->bus_type }}</td>
                </tr>
                <tr>
                    <th>Tanggal Keberangkatan</th>
                    <td>{{ $booking->ticket->departure_date }}</td>
                </tr>
                <tr>
                    <th>Tanggal Kedatangan</th>
                    <td>{{ $booking->ticket->arrival_date }}</td>
                </tr>
                <tr>
                    <th>Jumlah Kursi</th>
                    <td>{{ $booking->jumlah_kursi }}</td>
                </tr>
                <tr>
                    <th>Total Harga</th>
                    <td>Rp{{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge bg-{{ $booking->status == 'paid' ? 'success' : ($booking->status == 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                </tr>
            </table>
            <a href="{{ route('bookings.history') }}" class="btn btn-secondary">Kembali</a>
        </div>

        <!-- Bagian Gambar -->
        <div class="col-md-6 text-center">
            <img src="{{ asset('storage/' . $booking->ticket->image) }}" alt="Gambar Bus" class="img-fluid w-50 rounded shadow">
        </div>
    </div>
</div>
@endsection
