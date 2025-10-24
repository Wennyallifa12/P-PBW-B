<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesanTamu extends Model
{
    protected $fillable = ['nama', 'email', 'pesan'];
}
