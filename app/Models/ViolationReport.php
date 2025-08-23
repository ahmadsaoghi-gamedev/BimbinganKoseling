<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViolationReport extends Model
{
    use HasFactory;

    protected $table = 'violation_reports';

    protected $fillable = [
        'siswa_id',
        'gurubk_id',
        'category',
        'violation_description',
        'points_before',
        'points_after',
        'status',
        'sanctions',
        'prevention_actions',
        'special_notes',
        'violation_date',
        'parent_notification_sent',
        'summon_letter_sent',
    ];

    protected $casts = [
        'violation_date' => 'date',
        'parent_notification_sent' => 'boolean',
        'summon_letter_sent' => 'boolean',
    ];

    // Relationships
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function guruBk(): BelongsTo
    {
        return $this->belongsTo(GuruBK::class, 'gurubk_id');
    }

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Category constants
    const CATEGORY_LIGHT = 'light';
    const CATEGORY_MEDIUM = 'medium';
    const CATEGORY_HEAVY = 'heavy';
    const CATEGORY_VERY_HEAVY = 'very_heavy';

    // Constants arrays for views
    const STATUSES = [
        self::STATUS_PENDING => 'Menunggu',
        self::STATUS_PROCESSING => 'Diproses',
        self::STATUS_COMPLETED => 'Selesai',
        self::STATUS_CANCELLED => 'Dibatalkan',
    ];

    const CATEGORIES = [
        self::CATEGORY_LIGHT => 'Ringan',
        self::CATEGORY_MEDIUM => 'Sedang',
        self::CATEGORY_HEAVY => 'Berat',
        self::CATEGORY_VERY_HEAVY => 'Sangat Berat',
    ];

    // Point thresholds
    const THRESHOLD_ORANG_TUA = 70; // Poin untuk notifikasi orang tua
    const THRESHOLD_SURAT_PEMANGGILAN = 70; // Poin untuk surat pemanggilan

    /**
     * Accessor for status text
     */
    public function getStatusTextAttribute()
    {
        return self::STATUSES[$this->status] ?? 'Tidak Diketahui';
    }

    /**
     * Accessor for category text
     */
    public function getCategoryTextAttribute()
    {
        return self::CATEGORIES[$this->category] ?? 'Tidak Diketahui';
    }

    /**
     * Accessor for status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_PROCESSING => 'bg-blue-100 text-blue-800',
            self::STATUS_COMPLETED => 'bg-green-100 text-green-800',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Check if violation needs parent notification
     */
    public function needsParentNotification()
    {
        return $this->points_after >= self::THRESHOLD_ORANG_TUA;
    }

    /**
     * Check if violation needs summon letter
     */
    public function needsSummonLetter()
    {
        return $this->points_after >= self::THRESHOLD_SURAT_PEMANGGILAN;
    }

    /**
     * Get violations that need parent notification
     */
    public static function getViolationsNeedingParentNotification()
    {
        return self::where('points_after', '>=', self::THRESHOLD_ORANG_TUA)
                  ->where('parent_notification_sent', false)
                  ->get();
    }

    /**
     * Get violations that need summon letter
     */
    public static function getViolationsNeedingSummonLetter()
    {
        return self::where('points_after', '>=', self::THRESHOLD_SURAT_PEMANGGILAN)
                  ->where('summon_letter_sent', false)
                  ->get();
    }

    /**
     * Scope for pending violations
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for processing violations
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }

    /**
     * Scope for completed violations
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope for cancelled violations
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    /**
     * Scope for violations by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for violations by student
     */
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('siswa_id', $studentId);
    }

    /**
     * Scope for violations by guru BK
     */
    public function scopeByGuruBk($query, $guruBkId)
    {
        return $query->where('gurubk_id', $guruBkId);
    }

    /**
     * Calculate total points for student
     */
    public static function calculateTotalPointsForStudent($studentId)
    {
        return self::where('siswa_id', $studentId)
                  ->whereNotIn('status', [self::STATUS_CANCELLED])
                  ->sum('points_after');
    }

    /**
     * Get violation statistics for student
     */
    public static function getStudentViolationStats($studentId)
    {
        $violations = self::where('siswa_id', $studentId)
                         ->whereNotIn('status', [self::STATUS_CANCELLED]);

        return [
            'total_violations' => $violations->count(),
            'total_points' => $violations->sum('points_after'),
            'this_month' => $violations->whereMonth('created_at', now()->month)->count(),
            'by_category' => $violations->selectRaw('category, COUNT(*) as count')
                                      ->groupBy('category')
                                      ->pluck('count', 'category'),
            'by_status' => $violations->selectRaw('status, COUNT(*) as count')
                                    ->groupBy('status')
                                    ->pluck('count', 'status'),
        ];
    }
}
