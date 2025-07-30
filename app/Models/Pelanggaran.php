<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pelanggaran extends Model
{
    use HasFactory;

    protected $table = 'pelanggaran';
    protected $primarykey = 'id_siswa';

    protected $fillable = [
        'id_siswa',
        'jenis_pelanggaran',
        'point_pelanggaran',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id');
    }
}
