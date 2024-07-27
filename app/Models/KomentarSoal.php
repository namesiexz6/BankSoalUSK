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
        'id_user',
        'isi_komentar',
        'file_komentar',
        'parent_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

 
}
