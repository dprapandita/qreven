<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_order_id',
        'ticket_type_id',
        'kode_tiket',
        'status',
        'checked_in_at',
        'checked_in_by',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    public function ticketOrder()
    {
        return $this->belongsTo(TicketOrder::class);
    }

    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }

    public function checkInOfficer()
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }
}
