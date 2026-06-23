<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function index()
    {
        $pendaftaran = Pendaftaran::with([
            'pengguna',
            'kegiatan'
        ])->get();

        return view(
            'pendaftaran.index',
            compact('pendaftaran')
        );
    }

    public function create()
    {
        $kegiatan = Kegiatan::all();

        return view(
            'pendaftaran.create',
            compact('kegiatan')
        );
    }

    public function store(Request $request)
    {
        Pendaftaran::create([
            'pengguna_id' => auth()->id(),
            'kegiatan_id' => $request->kegiatan_id,
            'status' => 'pending'
        ]);

        return redirect()
            ->route('pendaftaran.index')
            ->with('success','Pendaftaran berhasil');
    }

    public function edit($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        return view(
            'pendaftaran.edit',
            compact('pendaftaran')
        );
    }

    public function update(Request $request,$id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        $pendaftaran->update([
            'status' => $request->status
        ]);

        return redirect()
            ->route('pendaftaran.index')
            ->with('success','Status berhasil diubah');
    }

    public function destroy($id)
    {
        Pendaftaran::destroy($id);

        return redirect()
            ->route('pendaftaran.index');
    }
}