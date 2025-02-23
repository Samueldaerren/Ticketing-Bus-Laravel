@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Dashboard Laporan</h2>
    
    <div class="row">
        <div class="col-md-6">
            <canvas id="chartStatus"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="chartBus"></canvas>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <canvas id="chartUsers"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="chartTickets"></canvas>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <canvas id="chartRevenue"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="chartBookings"></canvas>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <canvas id="chartRevenueMonthly"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="chartBookingsMonthly"></canvas>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <canvas id="chartRevenueYearly"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="chartBookingsYearly"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Status Pemesanan (Pending, Paid, Canceled)
    var ctxStatus = document.getElementById('chartStatus').getContext('2d');
    new Chart(ctxStatus, {
        type: 'bar',
        data: {
            labels: ['Pending', 'Paid', 'Canceled'],
            datasets: [{
                label: 'Jumlah Pemesanan',
                data: [{{ $status_pemesanan['pending'] ?? 0 }}, {{ $status_pemesanan['paid'] ?? 0 }}, {{ $status_pemesanan['canceled'] ?? 0 }}],
                backgroundColor: ['blue', 'green', 'red']
            }]
        }
    });

    // Tiket Terjual per Bus
    var ctxBus = document.getElementById('chartBus').getContext('2d');
    new Chart(ctxBus, {
        type: 'bar',
        data: {
            labels: {!! json_encode($tiket_per_bus->pluck('bus_name')) !!},
            datasets: [{
                label: 'Tiket Terjual',
                data: {!! json_encode($tiket_per_bus->pluck('total_terjual')) !!},
                backgroundColor: 'green'
            }]
        }
    });

    // Pengguna per Role
    var ctxUsers = document.getElementById('chartUsers').getContext('2d');
    new Chart(ctxUsers, {
        type: 'pie',
        data: {
            labels: ['User', 'Admin', 'Super Admin'],
            datasets: [{
                label: 'Total Pengguna',
                data: [{{ $total_users_user }}, {{ $total_users_admin }}, {{ $total_users_superadmin }}],
                backgroundColor: ['purple', 'orange', 'blue']
            }]
        }
    });

    // Total Tiket Aktif & Nonaktif
    var ctxTickets = document.getElementById('chartTickets').getContext('2d');
    new Chart(ctxTickets, {
        type: 'doughnut',
        data: {
            labels: ['Aktif', 'Nonaktif'],
            datasets: [{
                label: 'Total Tiket',
                data: [{{ $tickets_active }}, {{ $tickets_inactive }}],
                backgroundColor: ['green', 'red']
            }]
        }
    });

    // Grafik Pendapatan Harian
    var ctxRevenue = document.getElementById('chartRevenue').getContext('2d');
    new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels_harian) !!},
            datasets: [{
                label: 'Pendapatan Harian',
                data: {!! json_encode($data_pendapatan_harian) !!},
                borderColor: 'gold',
                fill: false
            }]
        }
    });

    // Grafik Booking Harian
    var ctxBookings = document.getElementById('chartBookings').getContext('2d');
    new Chart(ctxBookings, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels_harian) !!},
            datasets: [{
                label: 'Pemesanan Harian',
                data: {!! json_encode($data_booking_harian) !!},
                borderColor: 'blue',
                fill: false
            }]
        }
    });

    // Grafik Pendapatan Bulanan
    var ctxRevenueMonthly = document.getElementById('chartRevenueMonthly').getContext('2d');
    new Chart(ctxRevenueMonthly, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels_bulanan) !!},
            datasets: [{
                label: 'Pendapatan Bulanan',
                data: {!! json_encode($data_pendapatan_bulanan) !!},
                borderColor: 'gold',
                fill: false
            }]
        }
    });

    // Grafik Booking Bulanan
    var ctxBookingsMonthly = document.getElementById('chartBookingsMonthly').getContext('2d');
    new Chart(ctxBookingsMonthly, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels_bulanan) !!},
            datasets: [{
                label: 'Pemesanan Bulanan',
                data: {!! json_encode($data_booking_bulanan) !!},
                borderColor: 'blue',
                fill: false
            }]
        }
    });

    // Grafik Pendapatan Tahunan
    var ctxRevenueYearly = document.getElementById('chartRevenueYearly').getContext('2d');
    new Chart(ctxRevenueYearly, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels_tahunan) !!},
            datasets: [{
                label: 'Pendapatan Tahunan',
                data: {!! json_encode($data_pendapatan_tahunan) !!},
                borderColor: 'gold',
                fill: false
            }]
        }
    });

    // Grafik Booking Tahunan
    var ctxBookingsYearly = document.getElementById('chartBookingsYearly').getContext('2d');
    new Chart(ctxBookingsYearly, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels_tahunan) !!},
            datasets: [{
                label: 'Pemesanan Tahunan',
                data: {!! json_encode($data_booking_tahunan) !!},
                borderColor: 'blue',
                fill: false
            }]
        }
    });

</script>
@endsection
