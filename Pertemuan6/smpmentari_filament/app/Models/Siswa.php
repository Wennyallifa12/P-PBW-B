<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = [
        'nisn',
        'nama',
        'jenis_kelamin',
        'kelas',
        'tanggal_lahir',
        'alamat',
    ];
}
