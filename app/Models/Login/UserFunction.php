<?php

namespace App\Models\Login;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Login\RolFunction;

class UserFunction extends Model
{
    use HasFactory;

    protected $connection = 'mysql3';
    
    protected $table = 't_function';
    
    protected $fillable = [
        'id',
        'n_function_name',
        'n_description',
        'date_add',
        'user_edit',
        'date_edit',
        'user_delete',
        'date_delete',
    ];

    public function userFuncRolFunctions()
    {
        return $this->hasMany('App\Models\Login\RolFunction', 'id');
    }
    
    protected $hidden = [
        '',
    ];

    

}
