<?php

namespace App\Models\Login;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileRol extends Model
{
    use HasFactory;

    protected $connection = 'mysql3';
    
    protected $collection = 't_r_profile_rol';
    
    protected $fillable = [
        'id',
        'id_profiles',
	    'id_rol'
    ];

    protected $hidden = [
        '',
    ];

    
}
