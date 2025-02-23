<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_number',
        'bus_name',
        'capacity',
        'available_seats', 
        'bus_type',
        'origin',
        'destination',
        'departure_date',
        'arrival_date',  
        'price',
        'status',
        'image',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'ticket_id');
    }

    
}
