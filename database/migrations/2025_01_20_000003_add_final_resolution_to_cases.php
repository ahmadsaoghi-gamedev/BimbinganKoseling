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
        // Add final resolution fields to konsultasi table
        Schema::table('konsultasi', function (Blueprint $table) {
            $table->text('final_resolution')->nullable()->after('reply_date');
            $table->enum('case_status', ['open', 'in_progress', 'resolved', 'closed'])->default('open')->after('final_resolution');
            $table->timestamp('resolution_date')->nullable()->after('case_status');
            $table->unsignedBigInteger('resolved_by')->nullable()->after('resolution_date');
            $table->text('resolution_notes')->nullable()->after('resolved_by');
            $table->enum('resolution_type', ['counseling', 'disciplinary_action', 'mediation', 'referral', 'other'])->nullable()->after('resolution_notes');
            
            // Foreign key for resolved_by
            $table->foreign('resolved_by')->references('id')->on('users')->onDelete('set null');
        });

        // Add final resolution fields to pengaduan table
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->text('final_resolution')->nullable()->after('laporan_pengaduan');
            $table->enum('case_status', ['open', 'in_progress', 'resolved', 'closed'])->default('open')->after('final_resolution');
            $table->timestamp('resolution_date')->nullable()->after('case_status');
            $table->unsignedBigInteger('resolved_by')->nullable()->after('resolution_date');
            $table->text('resolution_notes')->nullable()->after('resolved_by');
            $table->enum('resolution_type', ['investigation', 'disciplinary_action', 'mediation', 'referral', 'other'])->nullable()->after('resolution_notes');
            
            // Foreign key for resolved_by
            $table->foreign('resolved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove fields from konsultasi table
        Schema::table('konsultasi', function (Blueprint $table) {
            $table->dropForeign(['resolved_by']);
            $table->dropColumn([
                'final_resolution',
                'case_status',
                'resolution_date',
                'resolved_by',
                'resolution_notes',
                'resolution_type'
            ]);
        });

        // Remove fields from pengaduan table
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->dropForeign(['resolved_by']);
            $table->dropColumn([
                'final_resolution',
                'case_status',
                'resolution_date',
                'resolved_by',
                'resolution_notes',
                'resolution_type'
            ]);
        });
    }
};
