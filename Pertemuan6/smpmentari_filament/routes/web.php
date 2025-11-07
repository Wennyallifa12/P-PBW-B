<?php

use Illuminate\Support\Facades\Route;

Route::view('/','welcome');
Route::get('/', function (){
    return view('kegiatan-public', ['items' => \App\Models\Kegiatan::latest()->paginate(9),
]);
});
