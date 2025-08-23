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
        Schema::table('rekap', function (Blueprint $table) {
            $table->enum('case_status', ['open', 'in_progress', 'resolved', 'closed'])->default('open')->after('balasan');
            $table->text('final_resolution')->nullable()->after('case_status');
            $table->timestamp('resolution_date')->nullable()->after('final_resolution');
            $table->unsignedBigInteger('resolved_by')->nullable()->after('resolution_date');
            $table->text('resolution_notes')->nullable()->after('resolved_by');
            $table->enum('resolution_type', ['counseling', 'disciplinary_action', 'mediation', 'referral', 'other'])->nullable()->after('resolution_notes');
            
            // Foreign key for resolved_by
            $table->foreign('resolved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekap', function (Blueprint $table) {
            $table->dropForeign(['resolved_by']);
            $table->dropColumn([
                'case_status',
                'final_resolution',
                'resolution_date',
                'resolved_by',
                'resolution_notes',
                'resolution_type'
            ]);
        });
    }
};
