<?php

namespace App\Http\Controllers;

use App\Models\PesanTamu;
use Illuminate\Http\Request;

class PesanTamuController extends Controller
{
    public function index()
    {
        $daftar_pesan = PesanTamu::latest()->get();
        return view('bukutamu.index', ['daftar_pesan' => $daftar_pesan]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'pesan' => 'required|string',
        ]);

        PesanTamu::create($validated);
        return redirect('/bukutamu')->with('success', 'Pesan Anda telah terkirim!');
    }

    // Admin methods
    public function adminIndex()
    {
        $daftar_pesan = PesanTamu::latest()->paginate(10);
        return view('admin.bukutamu.index', compact('daftar_pesan'));
    }

    public function destroy(PesanTamu $pesanTamu)
    {
        $pesanTamu->delete();
        return redirect()->route('bukutamu.admin.index')->with('success', 'Pesan berhasil dihapus!');
    }

    // public function create()
    // {
    //     return view('buku-tamu.create');
    // }
}
