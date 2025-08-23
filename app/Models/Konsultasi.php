<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Konsultasi extends Model
{
    use HasFactory;

    protected $table = 'konsultasi';
    protected $primaryKey = 'id';

    // Status constants
    const STATUS_BELUM_DIBACA = 'belum dibaca';
    const STATUS_SUDAH_DIBACA = 'sudah dibaca';
    const STATUS_DALAM_PERCAKAPAN = 'dalam percakapan';
    const STATUS_SELESAI = 'selesai';

    // Case status constants
    const CASE_STATUS_OPEN = 'open';
    const CASE_STATUS_IN_PROGRESS = 'in_progress';
    const CASE_STATUS_RESOLVED = 'resolved';
    const CASE_STATUS_CLOSED = 'closed';

    // Resolution type constants
    const RESOLUTION_TYPE_COUNSELING = 'counseling';
    const RESOLUTION_TYPE_DISCIPLINARY_ACTION = 'disciplinary_action';
    const RESOLUTION_TYPE_MEDIATION = 'mediation';
    const RESOLUTION_TYPE_REFERRAL = 'referral';
    const RESOLUTION_TYPE_OTHER = 'other';

    protected $fillable = [
        'id_siswa',
        'isi_curhat',
        'tgl_curhat',
        'status_baca',
        'attachment',
        'reply_guru',
        'reply_date',
        'final_resolution',
        'case_status',
        'resolution_date',
        'resolved_by',
        'resolution_notes',
        'resolution_type',
    ];

    protected $casts = [
        'tgl_curhat' => 'datetime',
        'reply_date' => 'datetime',
        'resolution_date' => 'datetime',
    ];

    /**
     * Get all valid status values
     */
    public static function getValidStatuses()
    {
        return [
            self::STATUS_BELUM_DIBACA,
            self::STATUS_SUDAH_DIBACA,
            self::STATUS_DALAM_PERCAKAPAN,
            self::STATUS_SELESAI,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_siswa', 'id');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(CurhatConversation::class, 'konsultasi_id');
    }

    public function latestConversation()
    {
        return $this->hasOne(CurhatConversation::class, 'konsultasi_id')->latest();
    }

    public function unreadConversations()
    {
        return $this->hasMany(CurhatConversation::class, 'konsultasi_id')->where('is_read', false);
    }

    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function siswa()
    {
        return $this->belongsTo(User::class, 'id_siswa');
    }

    /**
     * Get case status text
     */
    public function getCaseStatusTextAttribute()
    {
        $statuses = [
            self::CASE_STATUS_OPEN => 'Terbuka',
            self::CASE_STATUS_IN_PROGRESS => 'Sedang Diproses',
            self::CASE_STATUS_RESOLVED => 'Terselesaikan',
            self::CASE_STATUS_CLOSED => 'Ditutup',
        ];

        return $statuses[$this->case_status] ?? 'Tidak Diketahui';
    }

    /**
     * Get resolution type text
     */
    public function getResolutionTypeTextAttribute()
    {
        $types = [
            self::RESOLUTION_TYPE_COUNSELING => 'Konseling',
            self::RESOLUTION_TYPE_DISCIPLINARY_ACTION => 'Tindakan Disiplin',
            self::RESOLUTION_TYPE_MEDIATION => 'Mediasi',
            self::RESOLUTION_TYPE_REFERRAL => 'Rujukan',
            self::RESOLUTION_TYPE_OTHER => 'Lainnya',
        ];

        return $types[$this->resolution_type] ?? 'Tidak Diketahui';
    }

    /**
     * Get case status badge class
     */
    public function getCaseStatusBadgeClassAttribute()
    {
        $classes = [
            self::CASE_STATUS_OPEN => 'bg-yellow-100 text-yellow-800',
            self::CASE_STATUS_IN_PROGRESS => 'bg-blue-100 text-blue-800',
            self::CASE_STATUS_RESOLVED => 'bg-green-100 text-green-800',
            self::CASE_STATUS_CLOSED => 'bg-gray-100 text-gray-800',
        ];

        return $classes[$this->case_status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Check if case is resolved
     */
    public function isResolved()
    {
        return in_array($this->case_status, [self::CASE_STATUS_RESOLVED, self::CASE_STATUS_CLOSED]);
    }

    /**
     * Check if case is open
     */
    public function isOpen()
    {
        return $this->case_status === self::CASE_STATUS_OPEN;
    }

    /**
     * Check if case is in progress
     */
    public function isInProgress()
    {
        return $this->case_status === self::CASE_STATUS_IN_PROGRESS;
    }
}
