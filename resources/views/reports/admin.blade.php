@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Laporan Admin</h2>

    <table id="reportTable" class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Jumlah</th>
                <th>Total Pendapatan</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('styles')
    <!-- DataTables Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
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
    $('#reportTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('reports.data') }}", 
        dom: 'Bfrtip',
            buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
        columns: [
            { data: 'tanggal', name: 'tanggal' },
            { data: 'status', name: 'status' },
            { data: 'jumlah', name: 'jumlah' },
            { data: 'total', name: 'total' }
        ]
    });
});
</script>
@endpush
