<?php

namespace App\Models\Keycloak;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEntity extends Model
{
    use HasFactory;

   // protected $connection = 'mysql2';
    
    protected $table = 'USER_ENTITY';
   
    
    protected $fillable = [
        'ID',
        'EMAIL',
	    'EMAIL_CONSTRAINT',
	    'EMAIL_VERIFIED'
    ];

  

    protected $hidden = [
        '',
    ];
}
