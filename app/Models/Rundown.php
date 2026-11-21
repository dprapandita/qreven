<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rundown extends Model
{
    protected $fillable = [
        'event_id',
        'nama_kegiatan',
        'waktu_mulai',
        'waktu_selesai',
        'deskripsi'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}