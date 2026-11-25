<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketOrderItem extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'ticket_order_id',
        'ticket_type_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'subtotal' => 'decimal:2',
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
}
