<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('curhat_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('konsultasi_id');
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('guru_bk_id');
            
            // Kategori masalah
            $table->enum('kategori_masalah', [
                'akademik', 'sosial', 'pribadi', 'karir', 'keluarga', 
                'keuangan', 'kesehatan', 'lainnya'
            ]);
            
            // Status dan progress
            $table->enum('status_progress', [
                'baru', 'dalam_proses', 'menunggu_tindak_lanjut', 'selesai', 'ditutup'
            ])->default('baru');
            
            // Tingkat urgensi
            $table->enum('tingkat_urgensi', ['rendah', 'sedang', 'tinggi', 'kritis'])->default('rendah');
            
            // Analisis dan tindakan
            $table->text('analisis_masalah')->nullable();
            $table->text('tindakan_yang_dilakukan')->nullable();
            $table->text('hasil_tindakan')->nullable();
            $table->text('rekomendasi_lanjutan')->nullable();
            
            // Tracking waktu
            $table->timestamp('tanggal_curhat');
            $table->timestamp('tanggal_analisis')->nullable();
            $table->timestamp('tanggal_tindakan')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            
            // Metadata
            $table->integer('jumlah_sesi')->default(1);
            $table->boolean('perlu_tindak_lanjut')->default(false);
            $table->text('catatan_khusus')->nullable();
            
            $table->timestamps();

            // Foreign keys
            $table->foreign('konsultasi_id')->references('id')->on('konsultasi')->onDelete('cascade');
            $table->foreign('siswa_id')->references('id')->on('siswa')->onDelete('cascade');
            $table->foreign('guru_bk_id')->references('id')->on('gurubk')->onDelete('cascade');
            
            // Indexes
            $table->index(['siswa_id', 'status_progress']);
            $table->index(['kategori_masalah', 'tingkat_urgensi']);
            $table->index('tanggal_curhat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curhat_reports');
    }
};
