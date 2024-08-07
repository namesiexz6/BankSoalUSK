<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $table = 'post';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_mk',
        'id_user',
        'isi_post',
        'file_post',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function loves()
    {
        return $this->hasMany(LovePost::class, 'id_post');
    }
    public function Matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'id_mk');
    }
 
}
