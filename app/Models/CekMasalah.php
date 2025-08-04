<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CekMasalah extends Model
{
    use HasFactory;

    protected $table = 'cek_masalahs';
    
    protected $fillable = [
        'id_siswa',
        'kategori_masalah',
        'masalah_terpilih',
        'masalah_lain',
        'tingkat_urgensi',
        'deskripsi_tambahan',
        'status',
        'catatan_guru',
        'tindak_lanjut',
        'tanggal_review',
        'tanggal_tindak_lanjut',
    ];

    protected $casts = [
        'masalah_terpilih' => 'array',
        'tanggal_review' => 'datetime',
        'tanggal_tindak_lanjut' => 'datetime',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'reviewed' => 'bg-blue-100 text-blue-800',
            'follow_up' => 'bg-orange-100 text-orange-800',
            'completed' => 'bg-green-100 text-green-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getStatusTextAttribute()
    {
        $texts = [
            'pending' => 'Menunggu Review',
            'reviewed' => 'Sudah Direview',
            'follow_up' => 'Tindak Lanjut',
            'completed' => 'Selesai',
        ];

        return $texts[$this->status] ?? 'Unknown';
    }

    public function getUrgencyBadgeAttribute()
    {
        $badges = [
            'rendah' => 'bg-green-100 text-green-800',
            'sedang' => 'bg-yellow-100 text-yellow-800',
            'tinggi' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->tingkat_urgensi] ?? 'bg-gray-100 text-gray-800';
    }
}
