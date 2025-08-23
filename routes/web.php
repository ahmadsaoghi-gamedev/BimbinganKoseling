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

Route::get('/dashboard', function () { return view('dashboard'); })->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Data Siswa: CRUD operations hanya untuk admin & gurubk (harus didefinisikan dulu sebelum route show)
    Route::middleware('role:admin|gurubk')->group(function () {
        Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::patch('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
    });

    // Data Siswa: View untuk admin, gurubk, dan siswa
    Route::middleware('role:admin|gurubk|siswa')->group(function () {
        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
        Route::get('/siswa/{siswa}', [SiswaController::class, 'show'])->name('siswa.show');
    });

    // Data Guru BK: CRUD hanya untuk admin (harus didefinisikan dulu sebelum route show)
    Route::middleware('role:admin')->group(function () {
        Route::get('/guru_bk/create', [GuruBkController::class, 'create'])->name('guru_bk.create');
        Route::post('/guru_bk', [GuruBkController::class, 'store'])->name('guru_bk.store');
        Route::get('/guru_bk/{guru_bk}/edit', [GuruBkController::class, 'edit'])->name('guru_bk.edit');
        Route::patch('/guru_bk/{guru_bk}', [GuruBkController::class, 'update'])->name('guru_bk.update');
        Route::delete('/guru_bk/{guru_bk}', [GuruBkController::class, 'destroy'])->name('guru_bk.destroy');
    });
    
    // Data Guru BK: View untuk admin dan siswa
    Route::middleware('role:admin|siswa')->group(function () {
        Route::get('/guru_bk', [GuruBkController::class, 'index'])->name('guru_bk.index');
        Route::get('/guru_bk/{guru_bk}', [GuruBkController::class, 'show'])->name('guru_bk.show');
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

    // Routes untuk Daftar Cek Masalah - Siswa dan Guru BK bisa akses
    Route::middleware('role:siswa|gurubk')->group(function () {
        Route::get('/daftar-cek-masalah', [GuruBkController::class, 'daftarCekMasalah'])->name('gurubk.daftar-cek-masalah');
        Route::get('/siswa/cek-masalah/{id}/dcm-report', [App\Http\Controllers\SiswaCekMasalahController::class, 'showDCMReport'])->name('siswa.cek-masalah.dcm-report');
    });

    // Routes untuk Siswa mengisi Daftar Cek Masalah (hanya siswa yang bisa create)
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
        Route::get('/daftar-cek-masalah/{id}/surat-pemanggilan', [GuruBkController::class, 'cetakSuratPemanggilan'])->name('gurubk.cetak-surat-pemanggilan');
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
        Route::get('/rekap/create', [RekapController::class, 'create'])->name('rekap.create');
        Route::post('/rekap', [RekapController::class, 'store'])->name('rekap.store');
    });
    
    Route::middleware('role:gurubk')->group(function(){
        Route::get('/rekap/{rekap}/edit', [RekapController::class, 'edit'])->name('rekap.edit');
        Route::patch('/rekap/{rekap}', [RekapController::class, 'update'])->name('rekap.update');
        Route::delete('/rekap/{rekap}', [RekapController::class, 'destroy'])->name('rekap.destroy');
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

    // Curhat Reports Routes - Sistem Rekap Curhat Rahasia
    Route::middleware('role:gurubk|siswa|admin')->group(function () {
        Route::get('curhat-reports/dashboard', [App\Http\Controllers\CurhatReportController::class, 'dashboard'])->name('curhat-reports.dashboard');
        Route::get('curhat-reports/export', [App\Http\Controllers\CurhatReportController::class, 'export'])->name('curhat-reports.export');
        Route::resource('curhat-reports', App\Http\Controllers\CurhatReportController::class);
    });

    // Violation Reports Routes - Sistem Rekap Pelanggaran
    Route::middleware('role:gurubk|kesiswaan|admin|siswa')->group(function () {
        Route::get('violation-reports', [App\Http\Controllers\ViolationReportController::class, 'index'])->name('violation-reports.index');
    });

    // Admin, Guru BK, Kesiswaan can access all violation report features
    Route::middleware('role:gurubk|kesiswaan|admin')->group(function () {
        Route::get('violation-reports/dashboard', [App\Http\Controllers\ViolationReportController::class, 'dashboard'])->name('violation-reports.dashboard');
        Route::get('violation-reports/export', [App\Http\Controllers\ViolationReportController::class, 'export'])->name('violation-reports.export');
        Route::get('violation-reports/create', [App\Http\Controllers\ViolationReportController::class, 'create'])->name('violation-reports.create');
        Route::post('violation-reports', [App\Http\Controllers\ViolationReportController::class, 'store'])->name('violation-reports.store');
        Route::get('violation-reports/{violation_report}/edit', [App\Http\Controllers\ViolationReportController::class, 'edit'])->name('violation-reports.edit');
        Route::put('violation-reports/{violation_report}', [App\Http\Controllers\ViolationReportController::class, 'update'])->name('violation-reports.update');
        Route::patch('violation-reports/{violation_report}', [App\Http\Controllers\ViolationReportController::class, 'update'])->name('violation-reports.update');
        Route::delete('violation-reports/{violation_report}', [App\Http\Controllers\ViolationReportController::class, 'destroy'])->name('violation-reports.destroy');
    });

    // Violation Reports show route - accessible by all roles
    Route::middleware('role:gurubk|kesiswaan|admin|siswa')->group(function () {
        Route::get('violation-reports/{violation_report}', [App\Http\Controllers\ViolationReportController::class, 'show'])->name('violation-reports.show');
    });

    // Case Resolution Routes - Sistem Solusi Akhir
    Route::middleware('role:gurubk|admin|siswa')->group(function () {
        Route::get('case-resolution/dashboard', [App\Http\Controllers\CaseResolutionController::class, 'dashboard'])->name('case-resolution.dashboard');
        Route::get('case-resolution', [App\Http\Controllers\CaseResolutionController::class, 'index'])->name('case-resolution.index');
        Route::get('case-resolution/{id}/{type}', [App\Http\Controllers\CaseResolutionController::class, 'show'])->name('case-resolution.show');
        Route::patch('case-resolution/{id}/{type}', [App\Http\Controllers\CaseResolutionController::class, 'update'])->name('case-resolution.update');
        Route::get('case-resolution/generate-report', [App\Http\Controllers\CaseResolutionController::class, 'generateReport'])->name('case-resolution.generate-report');
    });

    // Summon Routes - Sistem Pemanggilan (Surat/WhatsApp/Email)
    Route::middleware('role:gurubk|admin|kesiswaan')->group(function () {
        Route::get('summons/dashboard', [App\Http\Controllers\SummonController::class, 'dashboard'])->name('summons.dashboard');
        Route::get('summons', [App\Http\Controllers\SummonController::class, 'index'])->name('summons.index');
        Route::get('summons/create', [App\Http\Controllers\SummonController::class, 'create'])->name('summons.create');
        Route::post('summons', [App\Http\Controllers\SummonController::class, 'store'])->name('summons.store');
        Route::get('summons/{summon}', [App\Http\Controllers\SummonController::class, 'show'])->name('summons.show');
        Route::get('summons/{summon}/edit', [App\Http\Controllers\SummonController::class, 'edit'])->name('summons.edit');
        Route::put('summons/{summon}', [App\Http\Controllers\SummonController::class, 'update'])->name('summons.update');
        Route::delete('summons/{summon}', [App\Http\Controllers\SummonController::class, 'destroy'])->name('summons.destroy');
        Route::post('summons/{summon}/send', [App\Http\Controllers\SummonController::class, 'send'])->name('summons.send');
        Route::post('summons/{summon}/mark-attended', [App\Http\Controllers\SummonController::class, 'markAttended'])->name('summons.mark-attended');
        Route::post('summons/{summon}/mark-not-attended', [App\Http\Controllers\SummonController::class, 'markNotAttended'])->name('summons.mark-not-attended');
        Route::post('summons/{summon}/cancel', [App\Http\Controllers\SummonController::class, 'cancel'])->name('summons.cancel');
        Route::post('summons/generate-template', [App\Http\Controllers\SummonController::class, 'generateTemplate'])->name('summons.generate-template');
        Route::post('summons/auto-generate', [App\Http\Controllers\SummonController::class, 'autoGenerateSummons'])->name('summons.auto-generate');
    });
});

require __DIR__ . '/auth.php';
