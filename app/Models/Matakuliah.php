<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model
{
    protected $table = 'matakuliah';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_semester',
        'kode',
        'nama',
        'sks',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
