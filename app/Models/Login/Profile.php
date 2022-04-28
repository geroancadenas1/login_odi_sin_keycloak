<?php

namespace App\Models\Login;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Login\ProfileRol;

class Profile extends Model
{
    use HasFactory;

    protected $connection = 'mysql3';
    
    protected $table = 't_profiles';
    
    protected $fillable = [
        'id',
        'id_dominio',
        'n_profile_name',
        'n_description',
        'date_add',
        'user_edit',
        'date_edit',
        'user_delete',
        'date_delete'
    ];

    public function rolProfileRols()
    {
        return $this->hasMany('App\Models\Login\ProfileRol', 'id');
    }


    protected $hidden = [
        '',
    ];
    
}
