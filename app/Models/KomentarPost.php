<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KomentarPost extends Model
{
    protected $table = 'komentar_post';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_post',
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
