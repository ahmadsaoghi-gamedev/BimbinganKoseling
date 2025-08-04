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

    // Data Siswa: Admin, gurubk, dan siswa
    Route::middleware('role:admin|gurubk|siswa')->group(function () {
        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
        Route::get('/siswa/{siswa}', [SiswaController::class, 'show'])->name('siswa.show');
    });

    // Data Siswa: CRUD operations hanya untuk admin & gurubk
    Route::middleware('role:admin|gurubk')->group(function () {
        Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::patch('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
    });

    // Data Guru BK: Hanya admin
    Route::middleware('role:admin')->group(function () {
        Route::resource('guru_bk', GuruBkController::class);
    });

    // Routes untuk Bimbingan Konseling - Siswa dan Guru BK
    Route::middleware('role:siswa|gurubk')->group(function () {
        Route::get('/konsultasi', [KonsultasiController::class, 'index'])->name('konsultasi.index');
        Route::get('/konsultasi/create', [KonsultasiController::class, 'create'])->name('konsultasi.create');
        Route::post('/konsultasi', [KonsultasiController::class, 'store'])->name('konsultasi.store');
        Route::get('/konsultasi/{konsultasi}', [KonsultasiController::class, 'show'])->name('konsultasi.show');
        Route::get('/siswa/konsultasi', [\App\Http\Controllers\SiswaConsultationController::class, 'index'])->name('siswa.konsultasi.index');
        Route::post('/konsultasi/balas', [KonsultasiController::class, 'balas'])->name('konsultasi.balas');
    });

    // Routes untuk Curhat Rahasia - Siswa dan Guru BK
    Route::middleware('role:siswa|gurubk')->group(function () {
        Route::get('/gurubk/curhat', [GuruBkController::class, 'listCurhat'])->name('gurubk.curhat');
        Route::post('/gurubk/curhat/{id}/reply', [GuruBkController::class, 'replyToConsultation'])->name('gurubk.curhat.reply');
    });

    // Routes untuk Bimbingan Lanjutan - Siswa dan Guru BK
    Route::middleware('role:siswa|gurubk')->group(function () {
        Route::get('/bimbingan-lanjutan', [GuruBkController::class, 'bimbinganLanjutan'])->name('gurubk.bimbingan-lanjutan');
    });

    // Routes untuk Daftar Cek Masalah - Hanya Guru BK yang bisa melihat hasil
    Route::middleware('role:gurubk')->group(function () {
        Route::get('/daftar-cek-masalah', [GuruBkController::class, 'daftarCekMasalah'])->name('gurubk.daftar-cek-masalah');
    });

    // Routes untuk Siswa mengisi Daftar Cek Masalah
    Route::middleware('role:siswa')->group(function () {
        Route::get('/siswa/cek-masalah/create', [App\Http\Controllers\SiswaCekMasalahController::class, 'create'])->name('siswa.cek-masalah.create');
        Route::post('/siswa/cek-masalah', [App\Http\Controllers\SiswaCekMasalahController::class, 'store'])->name('siswa.cek-masalah.store');
    });

    // Routes untuk Pengaduan - Siswa, Admin dan Guru BK
    Route::middleware('role:siswa|admin|gurubk')->group(function () {
        Route::get('/pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');
        Route::get('/pengaduan/{id}', [PengaduanController::class, 'show'])->name('pengaduan.show');
        Route::post('/pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store');
    });

    // Routes khusus Guru BK - CRUD operations
    Route::middleware('role:gurubk')->group(function(){
        // Curhat Rahasia - Management
        Route::patch('/gurubk/curhat/{id}/mark-read', [GuruBkController::class, 'markCurhatAsRead'])->name('gurubk.curhat.mark-read');
        
        // Bimbingan Lanjutan - Management
        Route::get('/bimbingan-lanjutan/create', [GuruBkController::class, 'createBimbinganLanjutan'])->name('gurubk.bimbingan-lanjutan.create');
        Route::post('/bimbingan-lanjutan', [GuruBkController::class, 'storeBimbinganLanjutan'])->name('gurubk.bimbingan-lanjutan.store');
        Route::get('/bimbingan-lanjutan/{id}/edit', [GuruBkController::class, 'editBimbinganLanjutan'])->name('gurubk.bimbingan-lanjutan.edit');
        Route::patch('/bimbingan-lanjutan/{id}', [GuruBkController::class, 'updateBimbinganLanjutan'])->name('gurubk.bimbingan-lanjutan.update');
        Route::delete('/bimbingan-lanjutan/{id}', [GuruBkController::class, 'destroyBimbinganLanjutan'])->name('gurubk.bimbingan-lanjutan.destroy');
        
        // Daftar Cek Masalah - Management
        Route::post('/daftar-cek-masalah', [GuruBkController::class, 'storeCekMasalah'])->name('gurubk.daftar-cek-masalah.store');
        Route::put('/daftar-cek-masalah/{id}/review', [GuruBkController::class, 'reviewCekMasalah'])->name('gurubk.review-cek-masalah');
    });

    // Routes khusus Admin dan Guru BK - Pengaduan Management
    Route::middleware('role:admin|gurubk')->group(function(){
        Route::get('/pengaduan/create', [PengaduanController::class, 'create'])->name('pengaduan.create');
        Route::get('/pengaduan/{id}/edit', [PengaduanController::class, 'edit'])->name('pengaduan.edit');
        Route::patch('/pengaduan/{id}', [PengaduanController::class, 'update'])->name('pengaduan.update');
        Route::delete('/pengaduan/{id}', [PengaduanController::class, 'destroy'])->name('pengaduan.destroy');
    });

    // Route untuk Rekap - Akses berdasarkan role
    Route::middleware('role:gurubk|siswa')->group(function(){
        Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');
    });
    
    Route::middleware('role:gurubk')->group(function(){
        Route::get('/rekap/create', [RekapController::class, 'create'])->name('rekap.create');
        Route::post('/rekap', [RekapController::class, 'store'])->name('rekap.store');
        Route::get('/rekap/{rekap}/edit', [RekapController::class, 'edit'])->name('rekap.edit');
        Route::patch('/rekap/{rekap}', [RekapController::class, 'update'])->name('rekap.update');
        Route::delete('/rekap/{rekap}', [RekapController::class, 'destroy'])->name('rekap.destroy');
    });

    // Route untuk siswa - hanya create dan view rekap
    Route::middleware('role:siswa')->group(function(){
        Route::get('/rekap/create', [RekapController::class, 'create'])->name('rekap.create');
        Route::post('/rekap', [RekapController::class, 'store'])->name('rekap.store');
    });

    // Data Pelanggaran: Kesiswaan (CRUD Lengkap) & Lainnya (hanya lihat)
    Route::middleware('role:kesiswaan|gurubk|kepsek|orangtua|kajur|siswa')->group(function () {
        Route::get('/pelanggaran', [PelanggaranController::class, 'index'])->name('pelanggaran.index');
    });

    Route::middleware('role:kesiswaan')->group(function () {
        Route::resource('pelanggaran', PelanggaranController::class)->except(['index']);
    });

    // WhatsApp Management Routes - Hanya untuk Admin dan Guru BK
    Route::middleware('role:admin|gurubk')->prefix('whatsapp')->name('whatsapp.')->group(function () {
        Route::get('/', [App\Http\Controllers\WhatsAppController::class, 'index'])->name('index');
        Route::get('/test-connection', [App\Http\Controllers\WhatsAppController::class, 'testConnection'])->name('test-connection');
        Route::post('/send-test', [App\Http\Controllers\WhatsAppController::class, 'sendTestMessage'])->name('send-test');
        Route::post('/update-guru-phone/{id}', [App\Http\Controllers\WhatsAppController::class, 'updateGuruBkPhone'])->name('update-guru-phone');
        Route::post('/update-siswa-phone/{id}', [App\Http\Controllers\WhatsAppController::class, 'updateSiswaPhone'])->name('update-siswa-phone');
        Route::post('/send-broadcast', [App\Http\Controllers\WhatsAppController::class, 'sendBroadcast'])->name('send-broadcast');
        Route::get('/logs', [App\Http\Controllers\WhatsAppController::class, 'viewLogs'])->name('logs');
    });
});

require __DIR__ . '/auth.php';
