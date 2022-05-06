<?php

namespace App\Models\Login;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Login\GenericPersonaEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class GenericEmail extends Model
{
    use HasFactory;

    //protected $connection = 'mysql4';
    
    protected $table = 'odi_generic.t_email';
   
    protected $fillable = [
        'ID',
        'n_email',
	    'n_default',
	    'user_add'
    ];

    public function genericPersonalEmail()
    {
        return $this->hasMany('App\Models\Login\GenericPersonaEmail', 'ID');
    }

    public function scopePersonalEmail(Builder $query)
    {
        $subqueryPerEmail = $query->join('odi_generic.t_r_persona_email', 'odi_generic.t_email.ID', '=', 'odi_generic.t_r_persona_email.id_email')
                            ->select('odi_generic.t_r_persona_email.id_persona');
            
    }


    protected $hidden = [
        '',
    ];
}
