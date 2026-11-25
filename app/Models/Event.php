<?php

namespace App\Models;

use App\Enums\EventRole;
use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nama_event',
        'subdomain',
        'deskripsi',
        'lokasi',
        'tanggal_mulai',
        'tanggal_selesai',
        'timezone',
        'status',
        'poster',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'datetime',
            'tanggal_selesai' => 'datetime',
            'status' => EventStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }

    public function ticketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class);
    }

    public function ticketOrders(): HasMany
    {
        return $this->hasMany(TicketOrder::class);
    }

    public function booths(): HasMany
    {
        return $this->hasMany(Booth::class);
    }

    public function rundowns(): HasMany
    {
        return $this->hasMany(Rundown::class);
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    /**
     * @param  array<int, EventRole>  $roles
     */
    public function hasMemberWithRole(User $user, array $roles = []): bool
    {
        $query = $this->members()->whereKey($user->id);

        if ($roles !== []) {
            $query->wherePivotIn('role', array_map(
                fn (EventRole $role): string => $role->value,
                $roles,
            ));
        }

        return $query->exists();
    }
}
