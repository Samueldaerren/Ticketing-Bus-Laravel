@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Daftar Pemesanan</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered" id="bookingTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama User</th>
                        <th>Bus</th>
                        <th>Jumlah Kursi</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        $('#bookingTable').DataTable({
    processing: true,
    serverSide: true,
    order: [[5, 'asc']],
    ajax: "{{ route('bookings.pushData') }}",
    dom: 'Bfrtip',
    buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
    columns: [
        { data: 'id', name: 'id' },
        { data: 'user_name', name: 'user_name' },
        { data: 'bus_name', name: 'bus_name', orderable: true }, // ✅ **Pastikan bus_name ada di sini**
        { data: 'jumlah_kursi', name: 'jumlah_kursi' },
        { data: 'total_harga', name: 'total_harga', orderable: true },
        { data: 'status', name: 'status', orderable: true },
        { data: 'action', name: 'action', orderable: false, searchable: false }
    ]
});

    </script>
@endpush
