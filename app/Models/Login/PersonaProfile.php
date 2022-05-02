<?php

namespace App\Models\Login;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Login\ProfileRol;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;



class PersonaProfile extends Model
{
    use HasFactory;

    protected $connection = 'mysql3';
    
    protected $table = 't_r_persona_profiles';
    
    protected $fillable = [
        'id',
        'id_persona',
	    'id_r_profile_rol'
        
    ];

    public function recibeProfileRolPer()
    {
        return $this->belongsTo('App\Models\Login\ProfileRol', 'id_r_profile_rol');
    }

    protected $hidden = [
        '',
    ];
    
}
