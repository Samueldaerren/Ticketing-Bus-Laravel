<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request; 
use Yajra\DataTables\Facades\DataTables;

class AdminReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('reports.admin');
    }

    public function getData()
{
    $data = Booking::selectRaw("DATE(created_at) as tanggal, status, COUNT(*) as jumlah, SUM(total_harga) as total")
        ->groupBy('tanggal', 'status')
        ->orderBy('tanggal', 'asc')
        ->get();

    return DataTables::of($data)
        ->editColumn('status', function ($data) {
            $statusMap = [
                'paid' => '<span class="badge bg-success">Paid</span>',
                'pending' => '<span class="badge bg-warning text-dark">Pending</span>',
                'canceled' => '<span class="badge bg-danger">Canceled</span>',
            ];

            return $statusMap[$data->status] ?? '<span class="badge bg-secondary">Unknown</span>';
        })
        ->editColumn('total', function ($data) {
            return $data->total ? 'Rp ' . number_format($data->total, 0, ',', '.') : '-';
        })
        ->rawColumns(['status']) 
        ->make(true);
}

}
