<?php

namespace App\Models\Login;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenericPersonaEmail extends Model
{
    use HasFactory;

    protected $connection = 'mysql4';
    
    protected $table = 't_r_persona_email';
   
    
    protected $fillable = [
        'id',
        'id_persona',
	    'id_email'
    ];

    public function genericEmail()
    {
        return $this->belongsTo('App\Models\Login\GenericEmail', 'id_email');
    }

    protected $hidden = [
        '',
    ];
}
