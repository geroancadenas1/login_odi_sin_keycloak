<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterLog extends Model
{
    use HasFactory;

    protected $table = 'REGISTER_LOGS';
    
    protected $fillable = [
        'ID',
        'PROCESO',
        'ACCION',
        'ID_REGISTRO',
        'DATA_PROCESO',
        'FECHA_PROCESO',
        'IP_LOCAL',
        'IP_REMOTE',
        'ID_USER',
        'UPDATED_AT',
        'CREATED_AT'
    ];

    protected $hidden = [
        '',
    ];


    public static function createLogs($proceso, $accion, $id_registro, $data_registro, $fecha_registro, $id_local, $ip_remote, $ip_user)
    {

        $data_control_log = [
            'PROCESO'       => $proceso,
            'ACCION'        => $accion,
            'ID_REGISTRO'   => $id_registro,
            'FECHA_PROCESO' => $fecha_registro,
            'DATA_PROCESO'  => $data_registro,
            'IP_LOCAL'      => $id_local,
            'IP_REMOTE'     => $ip_remote,
            'ID_USER'       => $ip_user

        ];
        $controlLogs = RegisterLog::create($data_control_log);

    }
    
}
