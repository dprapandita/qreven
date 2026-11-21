<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoothBooking extends Model
{
    protected $fillable = [
        'booth_id',
        'user_id',
        'status',
        'catatan'
    ];

    public function booth()
    {
        return $this->belongsTo(Booth::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}