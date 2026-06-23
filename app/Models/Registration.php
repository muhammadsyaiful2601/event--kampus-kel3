<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    // ✅ Protected Fillable - Mass Assignment
    protected $fillable = [
        'user_id',
        'event_id',
        'status',
    ];

    // ✅ Relasi belongsTo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ✅ Relasi belongsTo Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
