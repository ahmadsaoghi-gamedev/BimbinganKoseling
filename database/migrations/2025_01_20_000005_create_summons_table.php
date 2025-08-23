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
        Schema::create('summons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('created_by'); // Guru BK yang membuat
            $table->enum('type', ['letter', 'whatsapp', 'email'])->default('letter');
            $table->enum('status', ['draft', 'sent', 'delivered', 'read', 'attended', 'not_attended', 'cancelled'])->default('draft');
            $table->string('subject')->nullable();
            $table->text('content');
            $table->text('template_used')->nullable(); // Template yang digunakan
            $table->timestamp('scheduled_at')->nullable(); // Jadwal pemanggilan
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('attended_at')->nullable();
            $table->string('recipient_name')->nullable(); // Nama penerima (ortu/siswa)
            $table->string('recipient_contact')->nullable(); // Nomor/email penerima
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->json('metadata')->nullable(); // Data tambahan (violation details, etc.)
            $table->timestamps();

            // Foreign keys
            $table->foreign('siswa_id')->references('id')->on('siswa')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index(['siswa_id', 'status']);
            $table->index(['type', 'status']);
            $table->index('scheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summons');
    }
};
