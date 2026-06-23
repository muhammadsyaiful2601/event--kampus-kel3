<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $table = 'pendaftaran';

    protected $fillable = [
        'pengguna_id',
        'kegiatan_id',
        'status',
        'tanggal_daftar'
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class,'pengguna_id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class,'kegiatan_id');
    }
}