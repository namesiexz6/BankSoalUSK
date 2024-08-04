<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSubscription extends Model
{
    protected $fillable = [
        'user_id', 'id_mk', 'topic',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function Matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'id_mk');
    }
}
