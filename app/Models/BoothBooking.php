<?php

namespace App\Models;

use App\Enums\BoothBookingStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoothBooking extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'booth_id',
        'user_id',
        'status',
        'catatan',
        'approved_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => BoothBookingStatus::class,
            'approved_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function booth(): BelongsTo
    {
        return $this->belongsTo(Booth::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
