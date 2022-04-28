<?php

namespace App\Models\Keycloak;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAttribute extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    
    protected $table = 'USER_ATTRIBUTE';
   
    
    protected $fillable = [
        'ID',
        'NAME',
	    'VALUE',
	    'USER_ID'
        
    ];

  

    protected $hidden = [
        '',
    ];
}
