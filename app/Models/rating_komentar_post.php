<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rating_komentar_post extends Model
{
        
        protected $table = 'rating_komentar_post';
    
        protected $primaryKey = 'id';
    
        protected $fillable = [
            'id_komentar',
            'id_user',
            'rating',
            
        ];
    
        protected $casts = [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
