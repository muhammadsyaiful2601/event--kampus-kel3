<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    // ✅ Protected Fillable - Mass Assignment
    protected $fillable = [
        'user_id',
        'event_id',
        'participant_name',
        'team_name',
        'department',
        'year',
        'age',
        'participant_photo',
        'ticket_code',
        'status',
        'verified_at',
        'verified_by',
    ];

    /**
     * ✅ Casts attributes to specific types.
     */
    protected $casts = [
        'verified_at' => 'datetime',
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
