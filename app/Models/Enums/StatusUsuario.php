<?php

namespace App\Models\Enums;



class StatusUsuario extends Enum
{
    public const INACTIVO  = 0;
    public const ACTIVO    = 1;
    public const BLOQUEADO = 2;

    protected static function constantsAndStrings()
    {
        return array(
            self::INACTIVO  => "Ususario Inactivo",
            self::ACTIVO    => "Ususario Activo",
            self::BLOQUEADO => "Ususario Bloqueado",
        );
    }

    public static function toString($valor) : string
    {
        $arr = Self::constantsAndStrings();
        if(array_key_exists($valor, $arr)) {
            return $arr[$valor];
        }
        return "";
    }
    
}