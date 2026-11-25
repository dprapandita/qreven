<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'ticket_order_id',
        'ticket_type_id',
        'kode_tiket',
        'status',
        'checked_in_at',
        'checked_in_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => TicketStatus::class,
            'checked_in_at' => 'datetime',
        ];
    }

    public function ticketOrder(): BelongsTo
    {
        return $this->belongsTo(TicketOrder::class);
    }

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class)->withTrashed();
    }

    public function checkInOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }
}
