<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 1. MENGIMPOR CONTROLLER
use App\Http\Controllers\PageController;
use App\Http\Controllers\PesanTamuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// 2. GANTI RUTE ROOT DARI BREEZE DENGAN RUTE ASLI ANDA
Route::get('/', [PageController::class, 'home']);

// 3. TAMBAHKAN KEMBALI RUTE BUKU TAMU ANDA (INI PUBLIK, JADI DI LUAR MIDDLEWARE)
Route::get('/bukutamu', [PesanTamuController::class, 'index']);
Route::post('/bukutamu', [PesanTamuController::class, 'store']);


// 4. PERTAHANKAN RUTE-RUTE DARI BREEZE (PENTING UNTUK AUTH)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // 5. RESOURCE ROUTE UNTUK KEGIATAN (ADMIN ONLY)
    Route::resource('kegiatan', KegiatanController::class);

    // 6. ADMIN BUKU TAMU
    Route::get('/admin/bukutamu', [PesanTamuController::class, 'adminIndex'])->name('bukutamu.admin.index');
    Route::delete('/admin/bukutamu/{pesanTamu}', [PesanTamuController::class, 'destroy'])->name('bukutamu.admin.destroy');
    
    // 7. PENGATURAN WEBSITE
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';