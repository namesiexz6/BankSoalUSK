<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class multi_mk extends Model
{
    protected $table = 'multi_mk';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_mk',
        'id_semester',
        
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
