<?php

namespace App\Models;

use App\Enums\TicketOrderStatus;
use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketOrder extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'event_id',
        'total_harga',
        'status_pembayaran',
        'metode_pembayaran',
        'expires_at',
        'paid_at',
        'cancelled_at',
        'inventory_released_at',
    ];

    protected function casts(): array
    {
        return [
            'total_harga' => 'decimal:2',
            'status_pembayaran' => TicketOrderStatus::class,
            'expires_at' => 'datetime',
            'paid_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'inventory_released_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(TicketOrderItem::class);
    }

    public function isPaid(): bool
    {
        return $this->status_pembayaran === TicketOrderStatus::Paid;
    }

    public function isPending(): bool
    {
        return $this->status_pembayaran === TicketOrderStatus::Pending;
    }

    public function markPaid(): bool
    {
        if (! $this->isPending()) {
            return false;
        }

        if ($this->expires_at !== null && $this->expires_at->isPast()) {
            return false;
        }

        $this->forceFill([
            'status_pembayaran' => TicketOrderStatus::Paid,
            'paid_at' => now(),
        ])->save();

        return true;
    }

    public function expire(): bool
    {
        return $this->releaseInventory(TicketOrderStatus::Expired);
    }

    public function cancelPending(): bool
    {
        return $this->releaseInventory(TicketOrderStatus::Cancelled);
    }

    /**
     * Release reserved inventory exactly once for a pending order.
     *
     * Callers must lock the order row inside a transaction before calling this.
     */
    public function releaseInventory(TicketOrderStatus $terminalStatus): bool
    {
        if (! $this->isPending() || $this->inventory_released_at !== null) {
            return false;
        }

        $quantities = $this->items()
            ->select('ticket_type_id')
            ->selectRaw('SUM(quantity) as quantity')
            ->groupBy('ticket_type_id')
            ->pluck('quantity', 'ticket_type_id');

        if ($quantities->isEmpty()) {
            $quantities = $this->tickets()
                ->select('ticket_type_id')
                ->selectRaw('COUNT(*) as quantity')
                ->groupBy('ticket_type_id')
                ->pluck('quantity', 'ticket_type_id');
        }

        foreach ($quantities as $ticketTypeId => $quantity) {
            TicketType::withTrashed()
                ->whereKey($ticketTypeId)
                ->increment('kuota', (int) $quantity);
        }

        $this->tickets()
            ->where('status', '!=', TicketStatus::Cancelled->value)
            ->update(['status' => TicketStatus::Cancelled->value]);

        $this->forceFill([
            'status_pembayaran' => $terminalStatus,
            'inventory_released_at' => now(),
            'cancelled_at' => $terminalStatus === TicketOrderStatus::Cancelled ? now() : $this->cancelled_at,
        ])->save();

        return true;
    }
}
