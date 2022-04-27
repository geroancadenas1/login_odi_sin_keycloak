<?php

namespace App\Models\Login;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $connection = 'mysql3';
    
    protected $collection = 't_profiles';
    
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

    protected $hidden = [
        '',
    ];
    
}
