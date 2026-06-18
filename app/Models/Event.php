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
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
