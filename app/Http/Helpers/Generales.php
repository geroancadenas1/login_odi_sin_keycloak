<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

function EMailValido($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function Filtrar($variable, $tipo, $valor_si_incumple = null)
{
    switch (strtoupper($tipo)) {
        case "STRING":
        {
            if (!is_string($variable)) {
                $variable = $valor_si_incumple;
            }
            break;
        }
        case "EMAIL":
        {
            if (!filter_var($variable, FILTER_VALIDATE_EMAIL)) {
                $variable = $valor_si_incumple;
            }
            break;
        }
        case "BOOLEAN":
        {
            if(!is_bool($variable)){
                $variable = $valor_si_incumple;
            }
            break;
        }
        case "INTEGER":
        {
            if(!is_int($variable)){
                $variable = $valor_si_incumple;
                break;
            }
            if (filter_var($variable, FILTER_VALIDATE_INT) === false) {
                $variable = $valor_si_incumple;
            }
            break;
        }
        case "FLOAT":
        {
            if (!filter_var($variable, FILTER_VALIDATE_FLOAT)) {
                $variable = $valor_si_incumple;
            }
            break;
        }
        case "ARRAY":
        {
            if (!is_array($variable)) {
                $variable = $valor_si_incumple;
            }
            break;
        }
        case "OBJECT":
        {
            if (!is_object($variable)) {
                $variable = $valor_si_incumple;
            }
            break;
        }
        case "JSON":
        {
            if (json_decode($variable) === null) {
                $variable = $valor_si_incumple;
            }
            break;
        }
        case "URL":
        {
            if (filter_var($variable, FILTER_VALIDATE_URL) === false) {
                $variable = $valor_si_incumple;
            }
            break;
        }
    }
}