<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    protected $table = 'soal';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'id_mk',
        'nama_soal',
        'isi_soal',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
