<?php

namespace App\Models\Login;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonaProfile extends Model
{
    use HasFactory;

    protected $connection = 'mysql3';
    
    protected $collection = 't_r_persona_profiles';
    
    protected $fillable = [
        'id',
        'id_persona',
	    'id_r_profile_rol'
        
    ];

    protected $hidden = [
        '',
    ];
    
}
