<?php

namespace App\Models\Login;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenericEmail extends Model
{
    use HasFactory;

    protected $connection = 'mysql4';
    
    protected $table = 't_email';

    protected $primaryKey = 'id'; // Clave primaria
   
    protected $fillable = [
        'id',
        'n_email',
	    'n_default',
	    'user_add'
    ];

    public function genericPersonalEmail()
    {
        return $this->hasMany('App\Models\Login\GenericPersonaEmail', 'id');
    }

    protected $hidden = [
        '',
    ];
}
