<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduan';

    // Case status constants
    const CASE_STATUS_OPEN = 'open';
    const CASE_STATUS_IN_PROGRESS = 'in_progress';
    const CASE_STATUS_RESOLVED = 'resolved';
    const CASE_STATUS_CLOSED = 'closed';

    // Resolution type constants
    const RESOLUTION_TYPE_INVESTIGATION = 'investigation';
    const RESOLUTION_TYPE_DISCIPLINARY_ACTION = 'disciplinary_action';
    const RESOLUTION_TYPE_MEDIATION = 'mediation';
    const RESOLUTION_TYPE_REFERRAL = 'referral';
    const RESOLUTION_TYPE_OTHER = 'other';

    protected $fillable = [
        'nis',
        'jenis_pengaduan',
        'gambar',
        'tgl_pengaduan',
        'laporan_pengaduan',
        'final_resolution',
        'case_status',
        'resolution_date',
        'resolved_by',
        'resolution_notes',
        'resolution_type',
    ];

    protected $casts = [
        'tgl_pengaduan' => 'date',
        'resolution_date' => 'datetime',
    ];

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
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
            self::RESOLUTION_TYPE_INVESTIGATION => 'Investigasi',
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
