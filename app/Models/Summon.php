<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Summon extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'created_by',
        'type',
        'status',
        'subject',
        'content',
        'template_used',
        'scheduled_at',
        'sent_at',
        'delivered_at',
        'read_at',
        'attended_at',
        'recipient_name',
        'recipient_contact',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
        'attended_at' => 'datetime',
        'metadata' => 'array',
    ];

    // Type constants
    const TYPE_LETTER = 'letter';
    const TYPE_WHATSAPP = 'whatsapp';
    const TYPE_EMAIL = 'email';

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_SENT = 'sent';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_READ = 'read';
    const STATUS_ATTENDED = 'attended';
    const STATUS_NOT_ATTENDED = 'not_attended';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the student that owns the summon
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /**
     * Get the user who created the summon
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get status text for display
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_SENT => 'Terkirim',
            self::STATUS_DELIVERED => 'Terdeliver',
            self::STATUS_READ => 'Dibaca',
            self::STATUS_ATTENDED => 'Hadir',
            self::STATUS_NOT_ATTENDED => 'Tidak Hadir',
            self::STATUS_CANCELLED => 'Dibatalkan',
            default => 'Unknown',
        };
    }

    /**
     * Get type text for display
     */
    public function getTypeTextAttribute(): string
    {
        return match($this->type) {
            self::TYPE_LETTER => 'Surat',
            self::TYPE_WHATSAPP => 'WhatsApp',
            self::TYPE_EMAIL => 'Email',
            default => 'Unknown',
        };
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            self::STATUS_SENT => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            self::STATUS_DELIVERED => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            self::STATUS_READ => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
            self::STATUS_ATTENDED => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            self::STATUS_NOT_ATTENDED => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            self::STATUS_CANCELLED => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        };
    }

    /**
     * Get type badge class for UI
     */
    public function getTypeBadgeClassAttribute(): string
    {
        return match($this->type) {
            self::TYPE_LETTER => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            self::TYPE_WHATSAPP => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            self::TYPE_EMAIL => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        };
    }

    /**
     * Check if summon is draft
     */
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * Check if summon is sent
     */
    public function isSent(): bool
    {
        return in_array($this->status, [self::STATUS_SENT, self::STATUS_DELIVERED, self::STATUS_READ]);
    }

    /**
     * Check if summon is attended
     */
    public function isAttended(): bool
    {
        return $this->status === self::STATUS_ATTENDED;
    }

    /**
     * Check if summon is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Mark summon as sent
     */
    public function markAsSent(): void
    {
        $this->update([
            'status' => self::STATUS_SENT,
            'sent_at' => now(),
        ]);
    }

    /**
     * Mark summon as delivered
     */
    public function markAsDelivered(): void
    {
        $this->update([
            'status' => self::STATUS_DELIVERED,
            'delivered_at' => now(),
        ]);
    }

    /**
     * Mark summon as read
     */
    public function markAsRead(): void
    {
        $this->update([
            'status' => self::STATUS_READ,
            'read_at' => now(),
        ]);
    }

    /**
     * Mark summon as attended
     */
    public function markAsAttended(): void
    {
        $this->update([
            'status' => self::STATUS_ATTENDED,
            'attended_at' => now(),
        ]);
    }

    /**
     * Mark summon as not attended
     */
    public function markAsNotAttended(): void
    {
        $this->update([
            'status' => self::STATUS_NOT_ATTENDED,
        ]);
    }

    /**
     * Cancel summon
     */
    public function cancel(): void
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
        ]);
    }

    /**
     * Get scheduled date formatted
     */
    public function getScheduledDateFormattedAttribute(): string
    {
        return $this->scheduled_at ? $this->scheduled_at->format('d/m/Y H:i') : 'Belum dijadwalkan';
    }

    /**
     * Get sent date formatted
     */
    public function getSentDateFormattedAttribute(): string
    {
        return $this->sent_at ? $this->sent_at->format('d/m/Y H:i') : '-';
    }

    /**
     * Get attended date formatted
     */
    public function getAttendedDateFormattedAttribute(): string
    {
        return $this->attended_at ? $this->attended_at->format('d/m/Y H:i') : '-';
    }

    /**
     * Scope for pending summons (draft)
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    /**
     * Scope for sent summons
     */
    public function scopeSent($query)
    {
        return $query->whereIn('status', [self::STATUS_SENT, self::STATUS_DELIVERED, self::STATUS_READ]);
    }

    /**
     * Scope for attended summons
     */
    public function scopeAttended($query)
    {
        return $query->where('status', self::STATUS_ATTENDED);
    }

    /**
     * Scope for today's summons
     */
    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_at', today());
    }

    /**
     * Scope for this week's summons
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('scheduled_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Scope for this month's summons
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('scheduled_at', now()->month)
                    ->whereYear('scheduled_at', now()->year);
    }
}
