<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Added missing import for DB facade

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we need to do this differently
        // Let's create a new table with the correct structure
        Schema::create('violation_reports_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('gurubk_id')->constrained('gurubk')->onDelete('cascade');
            $table->enum('category', ['light', 'medium', 'heavy', 'very_heavy'])->default('medium');
            $table->text('violation_description');
            $table->integer('points_before')->default(0);
            $table->integer('points_after')->default(0);
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->text('sanctions')->nullable();
            $table->text('prevention_actions')->nullable();
            $table->text('special_notes')->nullable();
            $table->date('violation_date');
            $table->boolean('parent_notification_sent')->default(false);
            $table->boolean('summon_letter_sent')->default(false);
            $table->timestamps();
        });

        // Copy data from old table to new table
        DB::statement("
            INSERT INTO violation_reports_new (
                id, siswa_id, gurubk_id, category, violation_description, 
                points_before, points_after, status, sanctions, prevention_actions, 
                special_notes, violation_date, parent_notification_sent, 
                summon_letter_sent, created_at, updated_at
            )
            SELECT 
                id, siswa_id, guru_bk_id, 
                CASE 
                    WHEN kategori_pelanggaran = 'ringan' THEN 'light'
                    WHEN kategori_pelanggaran = 'sedang' THEN 'medium'
                    WHEN kategori_pelanggaran = 'berat' THEN 'heavy'
                    WHEN kategori_pelanggaran = 'sangat_berat' THEN 'very_heavy'
                    ELSE 'medium'
                END as category,
                COALESCE(deskripsi_pelanggaran, 'Pelanggaran siswa') as violation_description,
                COALESCE(total_poin_sebelumnya, 0) as points_before,
                COALESCE(poin_pelanggaran, 10) as points_after,
                CASE 
                    WHEN status_pelanggaran = 'baru' THEN 'pending'
                    WHEN status_pelanggaran = 'diproses' THEN 'processing'
                    WHEN status_pelanggaran = 'selesai' THEN 'completed'
                    WHEN status_pelanggaran = 'dibatalkan' THEN 'cancelled'
                    ELSE 'pending'
                END as status,
                sanksi_yang_diberikan as sanctions,
                tindakan_pencegahan as prevention_actions,
                catatan_khusus as special_notes,
                COALESCE(tanggal_pelanggaran, DATE('now')) as violation_date,
                COALESCE(notifikasi_ortu_dikirim, 0) as parent_notification_sent,
                COALESCE(surat_pemanggilan_dikirim, 0) as summon_letter_sent,
                created_at, updated_at
            FROM violation_reports
        ");

        // Drop old table and rename new table
        Schema::dropIfExists('violation_reports');
        Schema::rename('violation_reports_new', 'violation_reports');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a destructive migration, so we'll just recreate the old structure
        Schema::dropIfExists('violation_reports');
        
        Schema::create('violation_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('guru_bk_id')->constrained('gurubk')->onDelete('cascade');
            $table->string('kategori_pelanggaran');
            $table->string('jenis_pelanggaran');
            $table->text('deskripsi_pelanggaran')->nullable();
            $table->integer('poin_pelanggaran');
            $table->integer('total_poin_sebelumnya')->default(0);
            $table->integer('total_poin_setelahnya')->default(0);
            $table->string('status_pelanggaran');
            $table->text('sanksi_yang_diberikan')->nullable();
            $table->text('tindakan_pencegahan')->nullable();
            $table->text('catatan_khusus')->nullable();
            $table->datetime('tanggal_pelanggaran');
            $table->datetime('tanggal_pemrosesan')->nullable();
            $table->datetime('tanggal_sanksi')->nullable();
            $table->datetime('tanggal_selesai')->nullable();
            $table->boolean('notifikasi_ortu_dikirim')->default(false);
            $table->datetime('tanggal_notifikasi_ortu')->nullable();
            $table->text('pesan_notifikasi_ortu')->nullable();
            $table->boolean('surat_pemanggilan_dikirim')->default(false);
            $table->datetime('tanggal_surat_pemanggilan')->nullable();
            $table->string('nomor_surat_pemanggilan')->nullable();
            $table->string('lokasi_pelanggaran')->nullable();
            $table->string('saksi_pelanggaran')->nullable();
            $table->boolean('perlu_tindak_lanjut')->default(false);
            $table->text('rencana_tindak_lanjut')->nullable();
            $table->timestamps();
        });
    }
};
