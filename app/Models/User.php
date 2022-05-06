<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    //protected $connection = 'mysql2';

    protected $table = 'USER_ENTITY';
     
    protected $fillable = [
        'ID',
        'EMAIL',
        'EMAIL_CONSTRAINT',
        'EMAIL_VERIFIED',
        'FEDERATION_LINK',
        'FIRST_NAME',
        'LAST_NAME',
        'USERNAME'
    ];

    
    protected $hidden = [
        ''
    ];

    
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
