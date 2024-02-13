<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KomentarSoal extends Model
{
    protected $table = 'komentar_soal';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_soal',
        'nama_komentar',
        'isi_komentar',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

 
}
