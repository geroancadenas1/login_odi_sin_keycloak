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

  //  protected $connection = 'mysql3';
    
    protected $table = 'odi_seguridad.t_r_persona_profiles';
    
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
 
    public function scopeProfileFunctions(Builder $query)
    {
        $subqueryProfileFuncions = $query->join('odi_seguridad.t_r_profile_rol', 'odi_seguridad.t_r_persona_profiles.id_r_profile_rol', '=', 'odi_seguridad.t_r_profile_rol.id')
                                         ->join('odi_seguridad.t_profiles', 'odi_seguridad.t_r_profile_rol.id_profiles', '=', 'odi_seguridad.t_profiles.id')
                                         ->join('odi_seguridad.t_rol', 'odi_seguridad.t_rol.id', '=', 'odi_seguridad.t_r_profile_rol.id_rol')
                                         ->join('odi_seguridad.t_r_rol_function', 'odi_seguridad.t_rol.id', '=', 'odi_seguridad.t_r_rol_function.id_rol')
                                         ->join('odi_seguridad.t_function', 'odi_seguridad.t_function.id', '=', 'odi_seguridad.t_r_rol_function.id_function')
                                         ->select('odi_seguridad.t_profiles.n_description', 'odi_seguridad.t_profiles.n_profile_name', 'odi_seguridad.t_rol.n_description as rol_description', 'odi_seguridad.t_rol.n_profile_name as rol_profile_name');

                                
            
    }
    
}
