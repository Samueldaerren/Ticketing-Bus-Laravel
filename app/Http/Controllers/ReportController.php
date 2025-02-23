<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Total User per Role
        $total_users_admin = User::where('role', 'admin')->count();
        $total_users_superadmin = User::where('role', 'super-admin')->count();
        $total_users_user = User::where('role', 'user')->count();

        // Total Tiket Aktif & Nonaktif
        $tickets_active = Ticket::where('status', 'active')->count();
        $tickets_inactive = Ticket::where('status', 'inactive')->count();

        // Total Tiket Terjual per Bus
        $tiket_per_bus = Ticket::withCount(['bookings as total_terjual' => function ($query) {
            $query->where('status', 'paid');
        }])->get();

        // Status Pemesanan
        $status_pemesanan = Booking::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // === PENDAPATAN & BOOKING BERDASARKAN WAKTU ===
        
        // Harian (7 hari terakhir)
        $harian = Booking::where('status', 'paid')
            ->whereDate('created_at', '>=', Carbon::now()->subDays(7))
            ->selectRaw('DATE(created_at) as tanggal, SUM(total_harga) as pendapatan, COUNT(*) as jumlah_booking')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        $labels_harian = $harian->pluck('tanggal');
        $data_pendapatan_harian = $harian->pluck('pendapatan');
        $data_booking_harian = $harian->pluck('jumlah_booking');

        // Bulanan (6 bulan terakhir)
        $bulanan = Booking::where('status', 'paid')
            ->whereDate('created_at', '>=', Carbon::now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as bulan, SUM(total_harga) as pendapatan, COUNT(*) as jumlah_booking')
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get();

        $labels_bulanan = $bulanan->pluck('bulan');
        $data_pendapatan_bulanan = $bulanan->pluck('pendapatan');
        $data_booking_bulanan = $bulanan->pluck('jumlah_booking');

        // Tahunan (5 tahun terakhir)
        $tahunan = Booking::where('status', 'paid')
            ->whereYear('created_at', '>=', Carbon::now()->subYears(5)->year)
            ->selectRaw('YEAR(created_at) as tahun, SUM(total_harga) as pendapatan, COUNT(*) as jumlah_booking')
            ->groupBy('tahun')
            ->orderBy('tahun', 'asc')
            ->get();

        $labels_tahunan = $tahunan->pluck('tahun');
        $data_pendapatan_tahunan = $tahunan->pluck('pendapatan');
        $data_booking_tahunan = $tahunan->pluck('jumlah_booking');

        

        return view('reports.index', compact(
            'total_users_admin',
            'total_users_superadmin',
            'total_users_user',
            'tickets_active',
            'tickets_inactive',
            'status_pemesanan',
            'tiket_per_bus',
            'labels_harian', 'data_pendapatan_harian', 'data_booking_harian',
            'labels_bulanan', 'data_pendapatan_bulanan', 'data_booking_bulanan',
            'labels_tahunan', 'data_pendapatan_tahunan', 'data_booking_tahunan'
        ));
    }
}
