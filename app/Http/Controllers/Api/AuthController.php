<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enums\StatusUsuario;
use App\Models\Keycloak\UserAttribute;
use App\Models\Keycloak\UserEntity;
use App\Models\Login\GenericEmail;
use App\Models\Login\GenericPersonaEmail;
use App\Models\Login\Profile;
use App\Models\Login\PersonaProfile;
use App\Models\Login\UserFunction;
use App\Models\Login\RolFunction;
use App\Models\Login\ProfileRol;
use App\Models\Login\Rol;
use App\Models\RegisterLog;
use DateTime;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Fluent;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public static function encodeUuid($uuid): string
    {
        if (!Uuid::isValid($uuid)) {
            return $uuid;
        }
        if (!$uuid instanceof Uuid) {
            $uuid = Uuid::fromString($uuid);
        }
        return $uuid->getBytes();
    }

    public static function decodeUuid(string $binaryUuid): string
    {
        if (Uuid::isValid($binaryUuid)) {
            return $binaryUuid;
        }
        return Uuid::fromBytes($binaryUuid)->toString();
    }

    private static $messages = [
        'required' => 'El campo : token es obligatorio.'
    ];

    public function authenticateLogin(Request $request)
    {

             
        if ($request->isJson()) {

            $validaToken = null;
            $validar     = 0;
            $mensaje     = "";
            $ArrProfilesUsuario = array();
            $ArrFuncionesUsuario = array();
            $fecha_registro= Carbon::now(); 
            $id_local=$request->ip();
            $ip_remote=$_SERVER['REMOTE_ADDR'];
            $ip_user = $_SERVER['HTTP_USER_AGENT'];
            $resultadoFunciones = "";
            $auth = Auth::token();
            
            $valorToken = json_decode($auth, true); 

            try {
                if ($validar >= 0) {
                    $tokenId = $valorToken['sid'];
                    Filtrar($tokenId, "STRING");
                }
                if ($validar >= 0) {
                    $email = $valorToken['email'];
                    Filtrar($email, "STRING");
                }

                Log::error('Incicia el proceso de recuperación de funciones y perfiles para el usuario ' . $tokenId);

                if (empty($tokenId)) {
                    $validar = -1;
                    return response()->json(
                        [
                            'resultado' => false,
                            'mensaje' => "El campo 'TOKER' es requerido."
                        ]
                    );
                }
                if (empty($email)) {
                    $validar = -2;
                    return response()->json(
                        [
                            'resultado' => false,
                            'mensaje' => "El campo 'id_personal' es requerido."
                        ]
                    );
                }
                
                if ($validar >= 0) {
                          if($email != "")  
                            {                           
                                $GenericEmail = GenericEmail::PersonalEmail()->where('n_email', $email)->first();
                                                               
                                if($GenericEmail)
                                {   
                                    $id_personaDB =  $GenericEmail->id_persona;

                                 } else {
                                    $validar = -3;
                                    $id_personaDB = ""; 
                                    $mensaje = "No se encontró información del usuario que intenta loguearse";
                                }
                            } else {
                                $validar = -3;
                                $id_personaDB = ""; 
                                $mensaje = "No se encontró información del usuario que intenta loguearse";
                            }
                }

                if ($validar >= 0) {

                        $validaToken = $tokenId;

                        if ($validaToken == null) {
                            $validar = -4;
                            return response()->json(
                                [
                                    'resultado' => false,
                                    'mensaje' => "El usuario que intenta registrarse, no tiene un token activo"
                                ]
                            );
                        } else {
                            
                            $userPersonaProfiles = PersonaProfile::ProfileFunctions()->where('id_persona', $id_personaDB)->get(); 
                          
                                if ($userPersonaProfiles->count()) {
                                    $resultadoFunciones = "El usuario logueado cuenta con definición de perfiles y/o funciones";
                                } else  {
                                    $resultadoFunciones = "El usuario logueado no cuenta con perfiles y/o funciones asignadas";
                                }

                                foreach ($userPersonaProfiles as $userPersonaProfile) {
  
                                    $n_profile_name_pro           = $userPersonaProfile->n_profile_name;
                                    $n_description_pro            = $userPersonaProfile->n_description;
                                    $n_profile_name_rol           = $userPersonaProfile->rol_profile_name;
                                    $n_description_pro_rol        = $userPersonaProfile->rol_description;

                                    $ProfilesUsuario =  array(
                                        'id_persona'           => $tokenId,
                                        'n_profile_name_pro'   => $n_profile_name_pro,
                                        'n_description_pro'    => $n_description_pro,
                                        'n_profile_name_rol'   => $n_profile_name_rol,
                                        'n_description_rol'    => $n_description_pro_rol,
                                        'funciones'            => $ArrFuncionesUsuario
                                    );

                                    array_push($ArrProfilesUsuario, $ProfilesUsuario);
                                }

                                $objetoProfilesUsuario = [
                                    'persona_profile' => $ArrProfilesUsuario,
                                    'tipo' => "Objeto de profile, roles y funciones del usuario",
                                ];

                                if ($validar >= 0) {
                                    $status = 1;
                                    if ($status == StatusUsuario::INACTIVO) {
                                        $validar = -6;
                                        return response()->json(
                                            [
                                                'resultado' => false,
                                                'mensaje' => "El usuario que intenta ingresar, se encuentra inactivo."
                                            ]
                                        );
                                    }
                                }
                                if ($validar >= 0) {

                                    $data_registro = "ID DEL USUARIO:" . " - " . $tokenId . " - " .  "TOKEN DEL USUARIO:"  . " - " . $tokenId   . " - " .  "VALIDACIÓN DEL PROCESO:" . " - " . $validar ;
                                    
                                    RegisterLog::createLogs("Logueo","Inicio de Sesión", $tokenId,  $data_registro ,$fecha_registro, $id_local, $ip_remote, $ip_user);
                                  
                                    return response()->json(
                                        array(
                                            'resultado'      => true,
                                            'id_usuario'     => $tokenId,
                                            'email'          => $email,
                                            'mensaje'        => $resultadoFunciones,
                                            'objeto_usuario' => $objetoProfilesUsuario
                                        ),
                                        200
                                    );

                                } else {
                                    $objetoProfilesUsuario = [];
                                    return response()->json(
                                        array(
                                            'resultado'      => false,
                                            'id_usuario'     => $tokenId,
                                            'email'          => $email,
                                            'objeto_usuario' => $objetoProfilesUsuario
                                        ),
                                        400
                                    );
                                }
                        }
                    
                } else {
                    Log::error('No se encontró información del usuario que intenta loguearse');
                    return response()->json(
                        [
                            'resultado' => false,
                            'mensaje' => $mensaje
                        ]
                    );
                }
            } catch (Exception $ex) {
                Log::error('Ha ocurrido un error al obtener los perfiles del usuario ' . $ex);
                $validar = -8;
                return response()->json(
                    [
                        'resultado' => false,
                        'mensaje' =>  $ex
                    ]
                );
            }
        } else {

            Log::error('Proceso no atorizado, debe enviar los datos en un formato autorizado. ');
            $validar = -9;
            return response()->json(
                [
                    'resultado' => false,
                    'mensaje' => 'Proceso no atorizado, debe enviar los datos en un formato autorizado. '
                ],
                401,
                []
            );
        }

       Auth::token()->revoke();
    }

}
