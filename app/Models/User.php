<?php

namespace App\Models;

use DateTime;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'user';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'username',
        'password',
        'level',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function subscriptions()
    {
        return $this->hasMany(NotificationSubscription::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

   
}
