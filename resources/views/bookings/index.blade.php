@extends('layouts.app')

@section('content')
<div class="container">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <h2>Manajemen Pemesanan Tiket</h2>
    <a href="{{ route('bookings.create') }}" class="btn btn-primary mb-3">Tambah Booking</a>
    <table class="table table-bordered" id="bookingsTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Tiket</th>
                <th>Jumlah Kursi</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
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
    $(document).ready(function () {
        $('#bookingsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('bookings.data') }}",
            dom: 'Bfrtip',
                buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
            columns: [
                // { data: 'id', name: 'id' },
                // { data: 'user_name', name: 'user.name' },
                // { data: 'ticket_info', name: 'tickets.bus_number' },
                // { data: 'jumlah_kursi', name: 'jumlah_kursi' },
                // { data: 'total_harga', name: 'total_harga' },
                { data: 'id', name: 'bookings.id' },  
                { data: 'user_name', name: 'users.name' },  
                { data: 'ticket_info', name: 'tickets.bus_number' },  
                { data: 'jumlah_kursi', name: 'bookings.jumlah_kursi' },  
                { data: 'total_harga', name: 'bookings.total_harga' },  
                { data: 'status', name: 'status', searchable: true },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "→",
                    previous: "←"
                }
            }
        });
    });
</script>
@endpush
