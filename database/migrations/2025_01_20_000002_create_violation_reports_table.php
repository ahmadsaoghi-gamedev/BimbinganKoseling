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
        Schema::create('violation_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('guru_bk_id');
            
            // Kategori pelanggaran
            $table->enum('kategori_pelanggaran', [
                'ringan', 'sedang', 'berat', 'sangat_berat'
            ]);
            
            // Jenis pelanggaran detail
            $table->string('jenis_pelanggaran', 100);
            $table->text('deskripsi_pelanggaran')->nullable();
            
            // Poin dan tracking
            $table->integer('poin_pelanggaran');
            $table->integer('total_poin_sebelumnya')->default(0);
            $table->integer('total_poin_setelahnya')->default(0);
            
            // Status dan tindakan
            $table->enum('status_pelanggaran', [
                'baru', 'diproses', 'sanksi_diberikan', 'selesai', 'dibatalkan'
            ])->default('baru');
            
            $table->text('sanksi_yang_diberikan')->nullable();
            $table->text('tindakan_pencegahan')->nullable();
            $table->text('catatan_khusus')->nullable();
            
            // Tracking waktu
            $table->timestamp('tanggal_pelanggaran');
            $table->timestamp('tanggal_pemrosesan')->nullable();
            $table->timestamp('tanggal_sanksi')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            
            // Notifikasi tracking
            $table->boolean('notifikasi_ortu_dikirim')->default(false);
            $table->timestamp('tanggal_notifikasi_ortu')->nullable();
            $table->text('pesan_notifikasi_ortu')->nullable();
            
            $table->boolean('surat_pemanggilan_dikirim')->default(false);
            $table->timestamp('tanggal_surat_pemanggilan')->nullable();
            $table->text('nomor_surat_pemanggilan')->nullable();
            
            // Metadata
            $table->string('lokasi_pelanggaran', 100)->nullable();
            $table->string('saksi_pelanggaran', 100)->nullable();
            $table->boolean('perlu_tindak_lanjut')->default(false);
            $table->text('rencana_tindak_lanjut')->nullable();
            
            $table->timestamps();

            // Foreign keys
            $table->foreign('siswa_id')->references('id')->on('siswa')->onDelete('cascade');
            $table->foreign('guru_bk_id')->references('id')->on('gurubk')->onDelete('cascade');
            
            // Indexes
            $table->index(['siswa_id', 'status_pelanggaran']);
            $table->index(['kategori_pelanggaran', 'tanggal_pelanggaran']);
            $table->index('total_poin_setelahnya');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violation_reports');
    }
};
