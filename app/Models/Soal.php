<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    protected $table = 'soal';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_user',
        'id_mk',
        'nama_soal',
        'tipe',
        'isi_soal',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
