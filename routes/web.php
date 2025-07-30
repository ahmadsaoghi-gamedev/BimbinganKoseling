<?php

use App\Http\Controllers\OrangTuaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\GuruBkController;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\PengaduanController;
use App\Models\OrangTua;
use App\Models\Pengaduan;
use Database\Seeders\GuruBkSeeder;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

 Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
 Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
 Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
 Route::get('/siswa/{id}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
 Route::patch('/siswa/{id}', [SiswaController::class, 'update'])->name('siswa.update');
 Route::delete('/siswa/{id}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

 Route::get('/guru_bk', [GuruBkController::class, 'index'])->name('guru_bk.index');
 Route::get('/guru_bk/create', [GuruBkController::class, 'create'])->name('guru_bk.create');
 Route::post('/guru_bk', [GuruBkController::class, 'store'])->name('guru_bk.store');
 Route::get('/guru_bk/{id}/edit', [GuruBkController::class, 'edit'])->name('guru_bk.edit');
 Route::patch('/guru_bk/{id}', [GuruBkController::class, 'update'])->name('guru_bk.update');
 Route::delete('/guru_bk/{id}', [GuruBkController::class, 'destroy'])->name('guru_bk.destroy');

 Route::get('/pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');
 Route::get('/pengaduan/create', [PengaduanController::class, 'create'])->name('pengaduan.create');
 Route::post('/pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store');
 Route::get('/pengaduan/{id}/edit', [PengaduanController::class, 'edit'])->name('pengaduan.edit');
 Route::patch('/pengaduan/{id}', [PengaduanController::class, 'update'])->name('pengaduan.update');
 Route::delete('/pengaduan/{id}', [PengaduanController::class, 'destroy'])->name('pengaduan.destroy');
 
 Route::get('/pelanggaran', [PelanggaranController::class, 'index'])->name('pelanggaran.index');
 Route::get('/pelanggaran/create', [PelanggaranController::class, 'create'])->name('pelanggaran.create');
 Route::post('/pelanggaran', [PelanggaranController::class, 'store'])->name('pelanggaran.store');
 Route::get('/pelanggaran/{id}/edit', [PelanggaranController::class, 'edit'])->name('pelanggaran.edit');
 Route::patch('/pelanggaran/{id}', [PelanggaranController::class, 'update'])->name('pelanggaran.update');
 Route::delete('/pelanggaran/{id}', [PelanggaranController::class, 'destroy'])->name('pelanggaran.destroy');

 Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');
 Route::get('/rekap/create', [RekapController::class, 'create'])->name('rekap.create');
 Route::post('/rekap', [RekapController::class, 'store'])->name('rekap.store');
 Route::get('/rekap/edit/{id}', [RekapController::class, 'edit'])->name('rekap.edit');
 Route::patch('/rekap/{id}', [RekapController::class, 'update'])->name('rekap.update');
 Route::delete('/rekap/{id}', [RekapController::class, 'destroy'])->name('rekap.destroy');

 Route::get('/konsultasi', [RekapController::class, 'index'])->name('konsultasi.index');
 Route::get('/konsultasi/create', [RekapController::class, 'create'])->name('konsultasi.create');
 Route::post('/konsultasi', [RekapController::class, 'store'])->name('konsultasi.store');
 Route::get('/konsultasi/{id}/edit', [RekapController::class, 'edit'])->name('konsultasi.edit');
 Route::patch('/konsultasi/{id}', [RekapController::class, 'update'])->name('konsultasi.update');
 Route::delete('/konsultasi/{id}', [RekapController::class, 'destroy'])->name('konsultasi.destroy');

require __DIR__.'/auth.php';
