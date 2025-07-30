<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';
    protected $primarykey = 'id';

    protected $fillable = [
        'nis',
        'nama',
        'kelas',
        'jurusan',
        'jenis_kelamin',
        'no_tlp',
        'alamat',
        'id_user',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}


