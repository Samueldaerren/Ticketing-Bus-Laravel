<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function super()
    {
        return view('tickets.index');
    }

    public function admin()
    {
        return view('admin.index');
    }


    public function user()
    {
        $tickets = Ticket::where('status', 'Active')
        ->with('bookings')
        ->orderBy('created_at', 'desc') // Menampilkan tiket terbaru paling atas
        ->get();    
        return view('user.index', compact('tickets'));
    }
    // Method untuk menampilkan halaman dengan DataTables
    public function index()
    {
        return view('tickets.index');
    }

    // Method untuk mendapatkan data tiket dengan format DataTables
    public function getData()
{
    $tickets = Ticket::select(['id', 'bus_number', 'bus_name', 'capacity', 'bus_type', 'origin', 'destination', 'departure_date', 'arrival_date', 'price', 'status', 'image']);

    return DataTables::of($tickets)
        ->editColumn('price', function ($ticket) {
            return 'Rp ' . number_format($ticket->price, 2, ',', '.');
        })
        ->addColumn('image', function ($ticket) {
            $imageUrl = $ticket->image ? asset('storage/' . $ticket->image) : asset('images/default-ticket.jpg');
            return '<img src="' . $imageUrl . '" alt="Tiket" class="img-thumbnail" width="80">';
        })
        ->editColumn('status', function ($ticket) {
            $statusMap = [
                'active' => '<span class="badge bg-success">Active</span>',
                'inactive' => '<span class="badge bg-danger">Inactive</span>',
            ];

            return $statusMap[$ticket->status] ?? '<span class="badge bg-secondary">Unknown</span>';
        })
        ->addColumn('action', function ($ticket) {
            return '
                <a href="' . route('tickets.edit', $ticket->id) . '" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <form action="' . route('tickets.destroy', $ticket->id) . '" method="POST" style="display:inline;">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button class="btn btn-danger btn-sm" type="submit">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            ';
        })
        ->rawColumns(['image', 'status', 'action']) 
        ->make(true);
}
    
    // Method untuk menampilkan form create
    public function create()
    {
        return view('tickets.create');
    }

    // Method untuk menyimpan data tiket
    public function store(Request $request)
{
    $request->validate([
        'bus_number' => 'required',
        'bus_name' => 'required',
        'capacity' => 'required|integer|min:10',
        'bus_type' => 'required|string|in:Ekonomi,Bisnis,Eksekutif,VIP',
        'origin' => 'required',
        'destination' => 'required',
        'departure_date' => 'required|date',
        'arrival_date' => 'nullable|date',
        'price' => 'required|integer',
        'status' => 'required|in:active,inactive',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
    ]);

    $data = $request->all();

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('tickets', 'public');
    }

    $typePrices = [
        "Ekonomi" => 0,
        "Bisnis" => 50000,
        "Eksekutif" => 100000,
        "VIP" => 150000
    ];

    $data['price'] = $request->price + $typePrices[$request->bus_type];

    Ticket::create($data);

    return redirect()->route('tickets.index')->with('success', 'Tiket berhasil ditambahkan!');
}

    // Method untuk menampilkan form edit
    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('tickets.edit', compact('ticket'));
    }

    // Method untuk mengupdate data tiket
    public function update(Request $request, $id)
{
    $request->validate([
        'bus_number' => 'required',
        'bus_name' => 'required',
        'capacity' => 'required|integer|min:10',
        'bus_type' => 'required|string|in:Ekonomi,Bisnis,Eksekutif,VIP',
        'origin' => 'required',
        'destination' => 'required',
        'departure_date' => 'required|date',
        'arrival_date' => 'nullable|date',
        'price' => 'required|integer',
        'status' => 'required|in:active,inactive',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $ticket = Ticket::findOrFail($id);
    $data = $request->all();

    if ($request->hasFile('image')) {
        // Hapus gambar lama jika ada
        if ($ticket->image) {
            Storage::disk('public')->delete($ticket->image);
        }
        $data['image'] = $request->file('image')->store('tickets', 'public');
    }

    $typePrices = [
        "Ekonomi" => 0,
        "Bisnis" => 50000,
        "Eksekutif" => 100000,
        "VIP" => 150000
    ];

    $data['price'] = $request->price + $typePrices[$request->bus_type];

    $ticket->update($data);

    return redirect()->route('tickets.index')->with('success', 'Tiket berhasil diperbarui!');
}


    // Method untuk menghapus tiket
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        return redirect()->route('tickets.index');
    }
}

