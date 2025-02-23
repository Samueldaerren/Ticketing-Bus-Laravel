@extends('layouts.app')

@section('content')
<div class="container mt-5 d-flex justify-content-center">
    <div class="ticket-box shadow-lg p-4" id="ticket">
        <!-- Header Tiket -->
        <div class="text-center mb-3 border-bottom pb-2">
            <h3 class="fw-bold text-primary">E-Ticket Bus</h3>
            <small class="text-muted">Nomor Tiket: #{{ $booking->id }}</small>
        </div>

        <div class="row">
            <!-- Info Bus -->
            <div class="col-md-4 text-center">
                <img src="{{ asset('storage/' . $booking->ticket->image) }}" 
                     class="img-fluid rounded shadow" 
                     style="max-width: 100%; height: 150px; object-fit: cover;">
            </div>

            <!-- Detail Pemesanan -->
            <div class="col-md-8">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td><strong>Bus</strong></td>
                            <td>: {{ $booking->ticket->bus_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Rute</strong></td>
                            <td>: {{ $booking->ticket->origin }} → {{ $booking->ticket->destination }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tipe Bus</strong></td>
                            <td>: {{ $booking->ticket->bus_type }}</td>
                        </tr>
                        <tr>
                            <td><strong>Keberangkatan</strong></td>
                            <td>: {{ date('d M Y, H:i', strtotime($booking->ticket->departure_date)) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Kedatangan</strong></td>
                            <td>: {{ date('d M Y, H:i', strtotime($booking->ticket->arrival_date)) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Kursi</strong></td>
                            <td>: {{ $booking->jumlah_kursi }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Harga</strong></td>
                            <td>: Rp{{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>
                                : <span class="badge 
                                    {{ $booking->status == 'paid' ? 'bg-success' : 
                                       ($booking->status == 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tombol Cetak -->
        <div class="text-center mt-4 no-print">
            <button onclick="window.print()" class="btn btn-dark">
                <i class="fas fa-print"></i> Cetak Tiket
            </button>
        </div>
    </div>
</div>

<style>
    @media print {
        /* Hilangkan navbar dan tombol cetak */
        .navbar, .no-print {
            display: none !important;
        }

        /* Tiket tampak lebih profesional */
        .ticket-box {
            border: 2px dashed #333;
            padding: 20px;
            max-width: 600px;
            margin: auto;
            box-shadow: none;
        }

        /* Perbaiki ukuran font */
        body {
            font-size: 12px;
        }
    }
</style>
@endsection
