<?php

namespace App\Models\Login;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Login\Rol;
use App\Models\Login\UserFunction;

class RolFunction extends Model
{
    use HasFactory;

    protected $connection = 'mysql3';
    
    protected $table = 't_r_rol_function';
    
    protected $fillable = [
        'id',
        'id_rol',
	    'id_function'
    ];

    protected $hidden = [
        '',
    ];

    public function recibeUserFunction()
    {
        return $this->belongsTo('App\Models\Login\UserFunction', 'id_function');
    }

    public function recibeRol()
    {
        return $this->belongsTo('App\Models\Login\Rol', 'id_rol');
    }

}
