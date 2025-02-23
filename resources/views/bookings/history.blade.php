@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Riwayat Pemesanan</h2>
    <table class="table table-bordered" id="bookingTable">
        <thead>
            <tr>
                <th>Nomor Tiket</th>
                <th>Bus (Nama & Rute)</th>
                <th>Jumlah Kursi</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <!-- <tbody>
            @foreach($bookings as $booking)
            <tr>
                <td>{{ $booking->id }}</td>
                <td>{{ $booking->ticket->bus_name }} ({{ $booking->ticket->origin }} - {{ $booking->ticket->destination }})</td>
                <td>{{ $booking->jumlah_kursi }}</td>
                <td>Rp{{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                <td>
                    <span class="badge bg-{{ $booking->status == 'paid' ? 'success' : ($booking->status == 'pending' ? 'warning' : 'danger') }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-primary btn-sm">Lihat Detail</a>
                    @if($booking->status == 'paid')
                        <a href="{{ route('bookings.print', $booking->id) }}" class="btn btn-success btn-sm">Cetak Tiket</a>
                    @endif
                    @if($booking->status == 'pending')
                        <form action="{{ route('user.bookings.cancel', $booking->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin membatalkan?');">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody> -->
    </table>
</div>
@endsection

@push('styles')
    <!-- DataTables Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

@endpush

@push('scripts')
    <!-- Pastikan jQuery tidak double -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>

    <!-- DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

    <script>
    $(document).ready(function() {
    $('#bookingTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('helodata') }}',
        dom: 'Bfrtip',
        order: [[4, 'asc']],
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        columns: [
            { data: 'id', name: 'id' },
            { data: 'bus_info', name: 'bus_info' },
            { data: 'jumlah_kursi', name: 'jumlah_kursi' },
            { data: 'total_harga', name: 'total_harga' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});

</script>
@endpush
