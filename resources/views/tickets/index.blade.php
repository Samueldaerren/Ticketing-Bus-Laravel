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

        <h1>Manage Tickets</h1>
        <a href="{{ route('tickets.create') }}" class="btn btn-primary mb-3">Add Ticket</a>
        <table class="table table-striped" id="ticketsTable">
            <thead>
                <tr>
                    <th>Bus Number</th>
                    <th>Image</th>
                    <th>Bus Name</th>
                    <th>Bus Capacity</th>
                    <th>Bus Type</th>
                    <th>Origin</th>
                    <th>Destination</th>
                    <!-- <th>Departure Date</th> -->
                    <!-- <th>Arrival Date</th> -->
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
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
            $('#ticketsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('tickets.data') }}',
                dom: 'Bfrtip',
                buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                columns: [
                    { data: 'bus_number' },
                    { data: 'image', orderable: false, searchable: false },
                    { data: 'bus_name' },
                    { data: 'capacity' },
                    { data: 'bus_type' },
                    { data: 'origin' },
                    { data: 'destination' },
                    // { data: 'departure_date' },
                    // { data: 'arrival_date' },
                    { data: 'price' },
                    { data: 'status' }, 
                    { data: 'action', orderable: false, searchable: false },
                ]
            });
        });
    </script>
@endpush



