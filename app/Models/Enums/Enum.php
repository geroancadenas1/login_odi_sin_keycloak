<?php

namespace App\Models\Enums;

abstract class Enum
{
    abstract protected static function constantsAndStrings();

    public static function toString($valor) : string
    {
        $arr = Self::constantsAndStrings();
        if(array_key_exists($valor, $arr)) {
            return $arr[$valor];
        }
        return "";
    }
}