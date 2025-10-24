<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\Setting;

class PageController extends Controller
{
    public function home()
    {
        // Ambil jumlah kegiatan per halaman dari settings, default 6
        $perPage = Setting::get('home_kegiatan_per_page', 6);
        
        $kegiatan_terbaru = Kegiatan::latest()->paginate($perPage);
        
        return view('home', ['kegiatan_terbaru' => $kegiatan_terbaru]);
    }
}
