<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurhatReport extends Model
{
    use HasFactory;

    protected $table = 'curhat_reports';

    protected $fillable = [
        'konsultasi_id',
        'siswa_id',
        'guru_bk_id',
        'kategori_masalah',
        'status_progress',
        'tingkat_urgensi',
        'analisis_masalah',
        'tindakan_yang_dilakukan',
        'hasil_tindakan',
        'rekomendasi_lanjutan',
        'tanggal_curhat',
        'tanggal_analisis',
        'tanggal_tindakan',
        'tanggal_selesai',
        'jumlah_sesi',
        'perlu_tindak_lanjut',
        'catatan_khusus',
    ];

    protected $casts = [
        'tanggal_curhat' => 'datetime',
        'tanggal_analisis' => 'datetime',
        'tanggal_tindakan' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'perlu_tindak_lanjut' => 'boolean',
    ];

    // Relationships
    public function konsultasi(): BelongsTo
    {
        return $this->belongsTo(Konsultasi::class, 'konsultasi_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function guruBk(): BelongsTo
    {
        return $this->belongsTo(GuruBK::class, 'guru_bk_id');
    }

    // Status constants
    const STATUS_BARU = 'baru';
    const STATUS_DALAM_PROSES = 'dalam_proses';
    const STATUS_MENUNGGU_TINDAK_LANJUT = 'menunggu_tindak_lanjut';
    const STATUS_SELESAI = 'selesai';
    const STATUS_DITUTUP = 'ditutup';

    // Urgency constants
    const URGENCY_RENDAH = 'rendah';
    const URGENCY_SEDANG = 'sedang';
    const URGENCY_TINGGI = 'tinggi';
    const URGENCY_KRITIS = 'kritis';

    // Category constants
    const CATEGORY_AKADEMIK = 'akademik';
    const CATEGORY_SOSIAL = 'sosial';
    const CATEGORY_PRIBADI = 'pribadi';
    const CATEGORY_KARIR = 'karir';
    const CATEGORY_KELUARGA = 'keluarga';
    const CATEGORY_KEUANGAN = 'keuangan';
    const CATEGORY_KESEHATAN = 'kesehatan';
    const CATEGORY_LAINNYA = 'lainnya';

    /**
     * Get all valid status values
     */
    public static function getValidStatuses()
    {
        return [
            self::STATUS_BARU,
            self::STATUS_DALAM_PROSES,
            self::STATUS_MENUNGGU_TINDAK_LANJUT,
            self::STATUS_SELESAI,
            self::STATUS_DITUTUP,
        ];
    }

    /**
     * Get all valid urgency levels
     */
    public static function getValidUrgencies()
    {
        return [
            self::URGENCY_RENDAH,
            self::URGENCY_SEDANG,
            self::URGENCY_TINGGI,
            self::URGENCY_KRITIS,
        ];
    }

    /**
     * Get all valid categories
     */
    public static function getValidCategories()
    {
        return [
            self::CATEGORY_AKADEMIK,
            self::CATEGORY_SOSIAL,
            self::CATEGORY_PRIBADI,
            self::CATEGORY_KARIR,
            self::CATEGORY_KELUARGA,
            self::CATEGORY_KEUANGAN,
            self::CATEGORY_KESEHATAN,
            self::CATEGORY_LAINNYA,
        ];
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_BARU => 'bg-blue-100 text-blue-800',
            self::STATUS_DALAM_PROSES => 'bg-yellow-100 text-yellow-800',
            self::STATUS_MENUNGGU_TINDAK_LANJUT => 'bg-orange-100 text-orange-800',
            self::STATUS_SELESAI => 'bg-green-100 text-green-800',
            self::STATUS_DITUTUP => 'bg-gray-100 text-gray-800',
        ];

        return $badges[$this->status_progress] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get urgency badge class
     */
    public function getUrgencyBadgeAttribute()
    {
        $badges = [
            self::URGENCY_RENDAH => 'bg-green-100 text-green-800',
            self::URGENCY_SEDANG => 'bg-yellow-100 text-yellow-800',
            self::URGENCY_TINGGI => 'bg-orange-100 text-orange-800',
            self::URGENCY_KRITIS => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->tingkat_urgensi] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        $texts = [
            self::STATUS_BARU => 'Baru',
            self::STATUS_DALAM_PROSES => 'Dalam Proses',
            self::STATUS_MENUNGGU_TINDAK_LANJUT => 'Menunggu Tindak Lanjut',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_DITUTUP => 'Ditutup',
        ];

        return $texts[$this->status_progress] ?? 'Unknown';
    }

    /**
     * Get urgency text
     */
    public function getUrgencyTextAttribute()
    {
        $texts = [
            self::URGENCY_RENDAH => 'Rendah',
            self::URGENCY_SEDANG => 'Sedang',
            self::URGENCY_TINGGI => 'Tinggi',
            self::URGENCY_KRITIS => 'Kritis',
        ];

        return $texts[$this->tingkat_urgensi] ?? 'Unknown';
    }

    /**
     * Get category text
     */
    public function getCategoryTextAttribute()
    {
        $texts = [
            self::CATEGORY_AKADEMIK => 'Akademik',
            self::CATEGORY_SOSIAL => 'Sosial',
            self::CATEGORY_PRIBADI => 'Pribadi',
            self::CATEGORY_KARIR => 'Karir',
            self::CATEGORY_KELUARGA => 'Keluarga',
            self::CATEGORY_KEUANGAN => 'Keuangan',
            self::CATEGORY_KESEHATAN => 'Kesehatan',
            self::CATEGORY_LAINNYA => 'Lainnya',
        ];

        return $texts[$this->kategori_masalah] ?? 'Unknown';
    }

    /**
     * Scope for active reports
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status_progress', [self::STATUS_SELESAI, self::STATUS_DITUTUP]);
    }

    /**
     * Scope for urgent reports
     */
    public function scopeUrgent($query)
    {
        return $query->whereIn('tingkat_urgensi', [self::URGENCY_TINGGI, self::URGENCY_KRITIS]);
    }

    /**
     * Scope for reports by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('kategori_masalah', $category);
    }

    /**
     * Scope for reports by student
     */
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('siswa_id', $studentId);
    }

    /**
     * Scope for reports by guru BK
     */
    public function scopeByGuruBk($query, $guruBkId)
    {
        return $query->where('guru_bk_id', $guruBkId);
    }

    /**
     * Check if report is completed
     */
    public function isCompleted()
    {
        return in_array($this->status_progress, [self::STATUS_SELESAI, self::STATUS_DITUTUP]);
    }

    /**
     * Check if report is urgent
     */
    public function isUrgent()
    {
        return in_array($this->tingkat_urgensi, [self::URGENCY_TINGGI, self::URGENCY_KRITIS]);
    }

    /**
     * Get duration in days
     */
    public function getDurationInDaysAttribute()
    {
        if (!$this->tanggal_curhat) {
            return 0;
        }

        $endDate = $this->tanggal_selesai ?? now();
        return $this->tanggal_curhat->diffInDays($endDate);
    }

    /**
     * Mark as analyzed
     */
    public function markAsAnalyzed($analisis = null)
    {
        $this->update([
            'status_progress' => self::STATUS_DALAM_PROSES,
            'tanggal_analisis' => now(),
            'analisis_masalah' => $analisis ?? $this->analisis_masalah,
        ]);
    }

    /**
     * Mark as action taken
     */
    public function markAsActionTaken($tindakan = null)
    {
        $this->update([
            'status_progress' => self::STATUS_MENUNGGU_TINDAK_LANJUT,
            'tanggal_tindakan' => now(),
            'tindakan_yang_dilakukan' => $tindakan ?? $this->tindakan_yang_dilakukan,
        ]);
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted($hasil = null, $rekomendasi = null)
    {
        $this->update([
            'status_progress' => self::STATUS_SELESAI,
            'tanggal_selesai' => now(),
            'hasil_tindakan' => $hasil ?? $this->hasil_tindakan,
            'rekomendasi_lanjutan' => $rekomendasi ?? $this->rekomendasi_lanjutan,
        ]);
    }

    /**
     * Close report
     */
    public function closeReport($catatan = null)
    {
        $this->update([
            'status_progress' => self::STATUS_DITUTUP,
            'tanggal_selesai' => now(),
            'catatan_khusus' => $catatan ?? $this->catatan_khusus,
        ]);
    }
}
