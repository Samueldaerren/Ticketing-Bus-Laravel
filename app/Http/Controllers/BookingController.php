<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Auth;

class BookingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('bookings.index');
    }

    public function admin()
    {
        return view('admin.index');
    }

    public function pushData()
    {
        $bookings = Booking::with(['user', 'ticket']) // Pakai Eager Loading
            ->select([
                'bookings.*',
                \DB::raw('(SELECT bus_name FROM tickets WHERE tickets.id = bookings.ticket_id) as bus_name'),
                \DB::raw('(SELECT name FROM users WHERE users.id = bookings.user_id) as user_name') // ✅ Ambil nama user
            ]);
    
        return DataTables::of($bookings)
            ->addColumn('user_name', function ($booking) {
                return e(optional($booking->user)->name ?? 'N/A');
            })
            ->addColumn('bus_name', function ($booking) { // ✅ Ambil nama bus dari subquery
                return e($booking->bus_name ?? 'N/A');
            })
            ->filterColumn('bus_name', function ($query, $keyword) { // ✅ Agar bisa di-search
                $query->whereHas('ticket', function ($q) use ($keyword) {
                    $q->where('bus_name', 'like', "%{$keyword}%");
                });
            })
            ->orderColumn('bus_name', function ($query, $direction) { // ✅ Agar bisa di-sort
                $query->orderBy('bus_name', $direction);
            })
            ->filterColumn('user_name', function ($query, $keyword) { // ✅ Agar user_name bisa di-search
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->orderColumn('user_name', function ($query, $direction) { // ✅ Agar user_name bisa di-sort
                $query->orderBy('user_name', $direction);
            })
            ->addColumn('ticket_info', function ($booking) {
                return $booking->ticket
                    ? e("{$booking->ticket->bus_number} - {$booking->ticket->origin} → {$booking->ticket->destination}")
                    : '<span class="text-danger">Tiket Tidak Tersedia</span>';
            })
            ->editColumn('total_harga', function ($booking) {
                return 'Rp ' . number_format((float) $booking->total_harga, 2, ',', '.');
            })
            ->editColumn('status', function ($booking) {
                $statusMap = [
                    'pending' => 'warning',
                    'paid' => 'success',
                    'canceled' => 'danger',
                ];
                $badgeColor = $statusMap[strtolower($booking->status)] ?? 'secondary';
                return '<span class="badge bg-' . $badgeColor . '">' . ucfirst(e($booking->status)) . '</span>';
            })
            ->addColumn('action', function ($booking) {
                $buttons = '';
    
                if ($booking->status === 'pending') {
                    $buttons .= '
                        <form action="' . e(route('admin.bookings.confirm', $booking->id)) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . method_field('PUT') . '
                            <button class="btn btn-success btn-sm" type="submit">
                                <i class="bi bi-check-circle"></i> Konfirmasi
                            </button>
                        </form> ';
    
                    $buttons .= '
                        <form action="' . e(route('bookings.hapus', $booking->id)) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Yakin ingin membatalkan?\');">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button class="btn btn-warning btn-sm" type="submit">
                                <i class="bi bi-x-circle"></i> Cancel
                            </button>
                        </form>';
                }
    
                if (in_array($booking->status, ['paid', 'canceled'])) {
                    $buttons .= '
                        <form action="' . e(route('admin.bookings.delete', $booking->id)) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Yakin ingin menghapus booking ini?\');">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button class="btn btn-danger btn-sm" type="submit">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>';
                }
    
                return $buttons;
            })
            ->rawColumns(['status', 'action', 'ticket_info'])
            ->make(true);
    }
    
    

public function delete(Booking $booking)
{
    $booking->delete();
    return redirect()->route('admin-dashboard')->with('success', 'Booking berhasil dihapus permanen.');
}

    public function confirmPayment($id)
{
    $booking = Booking::findOrFail($id);

    if ($booking->status === 'pending') {
        $booking->update(['status' => 'paid']);
        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    return redirect()->back()->with('error', 'Booking sudah dibayar atau tidak valid.');
}


public function getData()
{
    $bookings = Booking::join('users', 'bookings.user_id', '=', 'users.id')
        ->join('tickets', 'bookings.ticket_id', '=', 'tickets.id')
        ->select(
            'bookings.*',
            'users.name as user_name',
            'tickets.bus_number',
            'tickets.origin',
            'tickets.destination',
            \DB::raw("CONCAT(tickets.bus_number, ' - ', tickets.origin, ' → ', tickets.destination) AS ticket_info")
        );

    return DataTables::of($bookings)
        ->editColumn('total_harga', fn($booking) => 'Rp ' . number_format($booking->total_harga, 2, ',', '.'))
        ->editColumn('status', function ($booking) {
            $statusMap = [
                'paid' => '<span class="badge bg-success">Paid</span>',  
                'pending' => '<span class="badge bg-warning text-dark">Pending</span>',  
                'canceled' => '<span class="badge bg-danger">Canceled</span>',  
            ];

            return $statusMap[$booking->status] ?? $booking->status;  
        })
        ->addColumn('action', function ($booking) {
            return '
                <a href="' . route('bookings.edit', $booking->id) . '" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <form action="' . route('bookings.destroy', $booking->id) . '" method="POST" style="display:inline;">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button class="btn btn-danger btn-sm" type="submit">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            ';
        })
        ->rawColumns(['status', 'action']) 
        ->make(true);
}



    public function create()
{
    $users = User::where('role', 'User')->get();
    $tickets = Ticket::where('status', 'active')->get()->map(function ($ticket) {
        $totalBooked = Booking::where('ticket_id', $ticket->id)
            ->whereIn('status', ['pending', 'paid'])
            ->sum('jumlah_kursi');
        $ticket->availableSeats = max(0, $ticket->capacity - $totalBooked);
        return $ticket;
    });

    return view('bookings.create', compact('users', 'tickets'));
}


    public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'ticket_id' => 'required|exists:tickets,id',
        'jumlah_kursi' => 'required|integer|min:1',
        'status' => 'required|in:pending,paid,canceled',
    ]);

    $ticket = Ticket::findOrFail($request->ticket_id);

    // Hitung ulang sisa kursi
    $totalBooked = Booking::where('ticket_id', $ticket->id)
        ->whereIn('status', ['pending', 'paid'])
        ->sum('jumlah_kursi');
    $availableSeats = max(0, $ticket->capacity - $totalBooked);

    if ($request->jumlah_kursi > $availableSeats) {
        return redirect()->back()->withInput()->with('error', "Jumlah kursi melebihi kapasitas yang tersedia ($availableSeats kursi tersisa).");
    }

    $booking = Booking::create([
        'user_id' => $request->user_id,
        'ticket_id' => $request->ticket_id,
        'jumlah_kursi' => $request->jumlah_kursi,
        'total_harga' => $ticket->price * $request->jumlah_kursi,
        'status' => $request->status,
    ]);

    return redirect()->route('bookings.index')->with('success', 'Booking berhasil ditambahkan.');
}



    public function edit(Booking $booking)
    {
        $users = User::where('role', 'User')->get();
        $tickets = Ticket::where('status', 'active')->get();
        return view('bookings.edit', compact('booking', 'users', 'tickets'));
    }

    public function update(Request $request, Booking $booking)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'ticket_id' => 'required|exists:tickets,id',
        'jumlah_kursi' => 'required|integer|min:1',
        'status' => 'required|in:pending,paid,canceled',
    ]);

    $ticket = Ticket::findOrFail($request->ticket_id);
    $kursiSebelumnya = $booking->jumlah_kursi; 
    $kursiBaru = $request->jumlah_kursi; 

    
    $totalBooked = Booking::where('ticket_id', $ticket->id)
        ->where('id', '!=', $booking->id) 
        ->whereIn('status', ['pending', 'paid'])
        ->sum('jumlah_kursi');

    
    $availableSeats = max(0, $ticket->capacity - $totalBooked);

    if ($kursiBaru > $availableSeats) {
        return redirect()->back()->withInput()->with('error', "Jumlah kursi melebihi kapasitas yang tersedia ($availableSeats kursi tersisa).");
    }

   
    $booking->update([
        'user_id' => $request->user_id,
        'ticket_id' => $request->ticket_id,
        'jumlah_kursi' => $kursiBaru,
        'total_harga' => $ticket->price * $kursiBaru,
        'status' => $request->status,
    ]);

    return redirect()->route('bookings.index')->with('success', 'Booking berhasil diperbarui.');
}


public function destroy(Booking $booking)
{
    if ($booking->status === 'paid') {
        $ticket = Ticket::findOrFail($booking->ticket_id);
        $ticket->increment('available_seats', $booking->jumlah_kursi);
    }

    $booking->delete();
    return redirect()->route('bookings.index')->with('success', 'Booking berhasil dihapus.');
}

// public function hapus(Booking $booking)
// {
//     if ($booking->status === 'paid') {
//         $ticket = Ticket::findOrFail($booking->ticket_id);
//         $ticket->increment('available_seats', $booking->jumlah_kursi);
//     }

//     $booking->delete();
//     return redirect()->route('admin-dashboard')->with('success', 'Booking berhasil dihapus.');
// }

public function hapus(Booking $booking)
{
    
    if ($booking->status !== 'Canceled') {
        
        $ticket = Ticket::findOrFail($booking->ticket_id);

        
        $ticket->increment('available_seats', $booking->jumlah_kursi);
    }

  
    $booking->update(['status' => 'Canceled']);

    return redirect()->route('admin-dashboard')->with('success', 'Booking berhasil dibatalkan dan kursi dikembalikan.');
}


public function order($id)
    {
        $ticket = Ticket::findOrFail($id);
        
    
        $totalBooked = Booking::where('ticket_id', $ticket->id)
            ->whereIn('status', ['pending', 'paid'])
            ->sum('jumlah_kursi');
        
        $availableSeats = max(0, $ticket->capacity - $totalBooked);
        
        return view('tickets.order', compact('ticket', 'availableSeats'));
    }

   
    public function jual(Request $request, $id)
{
    $request->validate([
        'jumlah_kursi' => 'required|integer|min:1'
    ]);

    $ticket = Ticket::findOrFail($id);


    $totalBooked = Booking::where('ticket_id', $ticket->id)
        ->whereIn('status', ['pending', 'paid'])
        ->sum('jumlah_kursi');

    $availableSeats = max(0, $ticket->capacity - $totalBooked);

 
    if ($request->jumlah_kursi > $availableSeats) {
        return redirect()->back()->withInput()->with('error', "Jumlah kursi melebihi kapasitas yang tersedia ($availableSeats kursi tersisa).");
    }

    
    Booking::create([
        'user_id' => Auth::id(),
        'ticket_id' => $ticket->id,
        'jumlah_kursi' => $request->jumlah_kursi,
        'total_harga' => $ticket->price * $request->jumlah_kursi,
        'status' => 'pending',
    ]);

    return redirect()->route('user-dashboard')->with('success', 'Pemesanan berhasil, menunggu konfirmasi admin!');
}

public function history()
{
    $bookings = Booking::where('user_id', Auth::id())->latest()->get();
    return view('bookings.history', compact('bookings'));
}

public function print($id)
{
    $booking = Booking::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
    return view('bookings.print', compact('booking'));
}

public function show($id)
{
    $booking = Booking::where('id', $id)->where('user_id', Auth::id())->with('ticket')->firstOrFail();
    return view('bookings.show', compact('booking'));
}

public function cancelBooking($id)
{
    $booking = Booking::where('id', $id)
                      ->where('user_id', auth()->id())
                      ->where('status', 'pending')
                      ->first();
    
    if (!$booking) {
        return redirect()->back()->with('error', 'Booking tidak dapat dibatalkan.');
    }

    $booking->status = 'canceled';
    $booking->save();

    return redirect()->back()->with('success', 'Booking berhasil dibatalkan.');
}

public function helodata()
{
    $bookings = Booking::with('ticket')
        ->join('tickets', 'bookings.ticket_id', '=', 'tickets.id') 
        ->where('bookings.user_id', auth()->id()) 
        ->select('bookings.*', 'tickets.bus_name', 'tickets.origin', 'tickets.destination'); 

    return DataTables::of($bookings)
        ->addColumn('bus_info', function ($booking) {
            return "{$booking->bus_name} ({$booking->origin} - {$booking->destination})";
        })
        ->editColumn('total_harga', function ($booking) {
            return 'Rp ' . number_format($booking->total_harga, 0, ',', '.');
        })
        ->editColumn('status', function ($booking) {
            $statusMap = [
                'paid' => ['label' => 'Paid', 'class' => 'success'],
                'pending' => ['label' => 'Pending', 'class' => 'warning'],
                'canceled' => ['label' => 'Canceled', 'class' => 'danger'],
            ];
            $status = $statusMap[$booking->status] ?? ['label' => 'Unknown', 'class' => 'secondary'];

            return '<span class="badge bg-' . $status['class'] . '">' . $status['label'] . '</span>';
        })
        ->addColumn('action', function ($booking) {
            $buttons = '<a href="' . route('bookings.show', $booking->id) . '" class="btn btn-primary btn-sm">Lihat Detail</a>';
            if ($booking->status == 'paid') {
                $buttons .= ' <a href="' . route('bookings.print', $booking->id) . '" class="btn btn-success btn-sm">Cetak Tiket</a>';
            }
            if ($booking->status == 'pending') {
                $buttons .= '<form action="' . route('user.bookings.cancel', $booking->id) . '" method="POST" style="display:inline;" onsubmit="return confirm(\'Yakin ingin membatalkan?\');">
                                ' . csrf_field() . method_field('PUT') . '
                                <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                             </form>';
            }
            return $buttons;
        })
        ->rawColumns(['bus_info', 'status', 'action'])
        ->filterColumn('bus_info', function($query, $keyword) {
            $query->whereRaw("CONCAT(tickets.bus_name, ' ', tickets.origin, ' ', tickets.destination) LIKE ?", ["%{$keyword}%"]);
        })
        ->orderColumn('bus_info', function($query, $order) {
            $query->orderBy('tickets.bus_name', $order);
        })
        ->make(true);
}


}
