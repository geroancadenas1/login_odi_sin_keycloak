<?php

namespace App\Models\Login;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolFunction extends Model
{
    use HasFactory;

    protected $connection = 'mysql3';
    
    protected $collection = 't_r_rol_function';
    
    protected $fillable = [
        'id',
        'id_rol',
	    'id_function'
    ];

    protected $hidden = [
        '',
    ];

    
}
