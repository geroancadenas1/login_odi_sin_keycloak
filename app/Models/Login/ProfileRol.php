<?php

namespace App\Models\Login;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Login\Rol;
use App\Models\Login\Profile;
use App\Models\Login\PersonaProfile;

class ProfileRol extends Model
{
    use HasFactory;

    protected $connection = 'mysql3';
    
    protected $table = 't_r_profile_rol';
    
    protected $fillable = [
        'id',
        'id_profiles',
	    'id_rol'
    ];


    public function recibeRol()
    {
        return $this->belongsTo('App\Models\Login\Rol', 'id_rol');
    }

    public function recibeProfileRol()
    {
        return $this->belongsTo('App\Models\Login\Profile', 'id_profiles');
    }

    public function ProfileRolPersonal()
    {
        return $this->hasMany('App\Models\Login\PersonaProfile', 'id');
    }

    protected $hidden = [
        '',
    ];

    
}
