<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = Kegiatan::all();

        return view(
            'kegiatan.index',
            compact('kegiatan')
        );
    }
}