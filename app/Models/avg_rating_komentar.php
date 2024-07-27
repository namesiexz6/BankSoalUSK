<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class avg_rating_komentar extends Model
{
    
    protected $table = 'avg_rating_komentar';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_komentar',
        'avg_rating',
        
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
