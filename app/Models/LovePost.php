<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LovePost extends Model
{
    protected $table = 'love_post';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_post',
        'id_user',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

 
}
