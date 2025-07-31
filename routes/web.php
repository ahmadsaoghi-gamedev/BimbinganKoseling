<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\GuruBkController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\KonsultasiController;

Route::get('/', function () { return view('welcome'); });

Route::get('/dashboard', function () { return view('dashboard'); })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Data Siswa: Hanya admin & gurubk
    Route::middleware('role:admin|gurubk')->group(function () {
        Route::resource('siswa', SiswaController::class);
    });

    // Data Guru BK: Hanya admin
    Route::middleware('role:admin')->group(function () {
        Route::resource('guru_bk', GuruBkController::class);
    });

    // Bimbingan, Curhat, Pengaduan: Siswa (membuat) & Guru BK (mengelola)
    Route::middleware('role:siswa')->group(function () {
        Route::get('/konsultasi/create', [KonsultasiController::class, 'create'])->name('konsultasi.create');
        Route::post('/konsultasi', [KonsultasiController::class, 'store'])->name('konsultasi.store');
        Route::post('/pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store');
        Route::get('/rekap/create', [RekapController::class, 'create'])->name('rekap.create');
    });

    Route::middleware('role:gurubk')->group(function(){
        Route::get('/konsultasi', [KonsultasiController::class, 'index'])->name('konsultasi.index');
        Route::get('/konsultasi/{konsultasi}', [KonsultasiController::class, 'show'])->name('konsultasi.show');
        Route::post('/konsultasi/balas', [KonsultasiController::class, 'balas'])->name('konsultasi.balas');
        Route::resource('rekap', RekapController::class)->except(['create']);
        Route::resource('pengaduan', PengaduanController::class)->except(['store']);
    });

    // Data Pelanggaran: Kesiswaan (CRUD Lengkap) & Lainnya (hanya lihat)
    Route::middleware('role:kesiswaan|gurubk|kepsek|orangtua|kajur|siswa')->group(function () {
        Route::get('/pelanggaran', [PelanggaranController::class, 'index'])->name('pelanggaran.index');
    });

    Route::middleware('role:kesiswaan')->group(function () {
        Route::resource('pelanggaran', PelanggaranController::class)->except(['index']);
    });
});

require __DIR__ . '/auth.php';
