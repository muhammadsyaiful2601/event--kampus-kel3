<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'date',
        'location',
        'image',
        'quota',
        'status',
        'type',
        'is_registration_open',
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function getIsFullAttribute()
    {
        if (!$this->quota) {
            return false;
        }
        return $this->registrations()->count() >= $this->quota;
    }
}
