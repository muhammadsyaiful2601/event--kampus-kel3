<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';

    protected $fillable = [
        'nama_kegiatan',
        'deskripsi',
        'tanggal',
        'waktu',
        'lokasi',
        'poster',
        'kuota'
    ];

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class);
    }
}