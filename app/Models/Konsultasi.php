<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Konsultasi extends Model
{
    use HasFactory;

    protected $table = 'konsultasi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_siswa',
        'isi_curhat',
        'tgl_curhat',
        'status_baca',
        'attachment',
    ];

    protected $casts = [
        'tgl_curhat' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_siswa', 'id');
    }
}
