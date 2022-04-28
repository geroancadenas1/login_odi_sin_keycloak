<?php

namespace App\Models\Login;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Login\RolFunction;
use App\Models\Login\ProfileRol;


class Rol extends Model
{
    use HasFactory;

    protected $connection = 'mysql3';
    
    protected $table = 't_rol';
    
    protected $fillable = [
        'id',
        'n_profile_name',
        'n_description',
        'date_add',
        'user_edit',
        'date_edit',
        'user_delete',
        'date_delete'
    ];

    public function rolRolFunction()
    {
        return $this->hasMany('App\Models\Login\RolFunction', 'id');
    }

    public function rolRol()
    {
        return $this->hasMany('App\Models\Login\ProfileRol', 'id');
    }

    protected $hidden = [
        '',
    ];

    
}
