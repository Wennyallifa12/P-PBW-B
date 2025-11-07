<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $fillable = [
        'judul',
        'tanggal',
        'ringkasan',
        'isi',
        'foto',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
