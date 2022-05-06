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
            $USER_ID = "";

            
            try {

                $request->validate(
                    [
                        'token' => 'required'
                    ],
                    self::$messages
                );

                if ($validar >= 0) {
                    $token = $request->input('token');
                    Filtrar($token, "STRING");
                }
                if ($validar >= 0) {
                    $id_persona = $request->input('id_persona');
                    Filtrar($id_persona, "STRING");
                }

                Log::error('Incicia el proceso de recuperación de funciones y perfiles para el usuario ' . $token);

                if (empty($token)) {
                    $validar = -1;
                    return response()->json(
                        [
                            'resultado' => false,
                            'mensaje' => "El campo 'TOKER' es requerido."
                        ]
                    );
                }
                if (empty($id_persona)) {
                    $validar = -2;
                    return response()->json(
                        [
                            'resultado' => false,
                            'mensaje' => "El campo 'id_personal' es requerido."
                        ]
                    );
                }
                
                if ($validar >= 0) {
                    $UserAttribute = UserAttribute::where('USER_ID', '3242242')->first();
                   // $UserAttribute = UserAttribute::where('USER_ID', $id_persona)->first();
                  
                    if($UserAttribute == null) {

                        

                        $UserEntity = UserEntity::where('ID', $id_persona)->first();
                       
                       // dd($UserEntity->ID, $UserEntity->EMAIL);
                       
                        if($UserEntity)
                        {
                            $n_email = $UserEntity->EMAIL;
                           
                            if($n_email != "")  
                            {

                                  $GenericEmail = GenericEmail::select('id', 'n_email')->where('n_email', $n_email)->first();

                               //dd($GenericEmail->n_email, $GenericEmail->id);
                               dd($GenericEmail); 

                                if($GenericEmail)
                                {
                                  
                                    $id_email=$GenericEmail->id;
                                     // dd($id_email );
                                                                       
                                    $GenericPersonaEmail = $GenericEmail->genericPersonalEmail()->where('id', $id_email)->first(); 

                                   dd($GenericPersonaEmail );

                                    if($GenericPersonaEmail)
                                    {
                                        $id_personaDB =  $GenericPersonaEmail->id_persona;  
                                        $id_personaDB = $this->decodeUuid($GenericPersonaEmail->id_persona); //solo para ver
                                        
                                        
                                    } else {
                                        $id_personaDB = ""; 
                                    }
                                } else {
                                    $validar = -3;
                                    $mensaje = "No se encontró información del usuario que intenta loguearse";
                                }
                            } else {
                                $validar = -3;
                                $mensaje = "No se encontró información del usuario que intenta loguearse";
                            }

                        } else {
                            $validar = -3;
                            $mensaje = "No se encontró información del usuario que intenta loguearse";
                        }

                    } else {

                        $USER_ID =  $UserAttribute->USER_ID;  
                        $isUuid = Str::isUuid($USER_ID);
                            if ($isUuid) {
                                $id_personaDB = $this->encodeUuid($USER_ID);
                            } else {
                                $id_personaDB = "";
                            }
                        
                    }
                }

                if ($validar >= 0) {

                        $validaToken = $token;

                        if ($validaToken == null) {
                            $validar = -4;
                            return response()->json(
                                [
                                    'resultado' => false,
                                    'mensaje' => "El usuario que intenta registrarse, no tiene un token activo"
                                ]
                            );
                        } else {
                            
                            $userPersonaProfiles = PersonaProfile::where('id_persona', $id_personaDB)->get(); 

                            if ($userPersonaProfiles->count()) {

                                foreach ($userPersonaProfiles as $userPersonaProfile) {

                                    $id_r_profile_rol = $userPersonaProfile->id_r_profile_rol;
                                    $id_r_profile_rolDecodificado = $this->decodeUuid($id_r_profile_rol);

                                    $profileRol = $userPersonaProfile->recibeProfileRolPer()->where('id', $id_r_profile_rol)->first();  

                                    if ($profileRol) {
                                        $id_rol = $profileRol['id_rol'];
                                        $id_profile = $profileRol['id_profiles'];

                                        $profile = Profile::where('id', $id_profile)->first();
                                        if ($profile) {
                                            $n_profile_name_pro = $profile->n_profile_name;
                                            $n_description_pro  = $profile->n_description;
                                        } else {
                                            $n_profile_name_pro  = "";
                                            $n_description_pro = "";
                                        }
                                        $rol = Rol::where('id', $id_rol)->first();
                                        if ($rol) {
                                            $n_profile_name_rol = $rol->n_profile_name;
                                            $n_description_pro_rol = $rol->n_description;
                                        } else {
                                            $n_profile_name_rol = "";
                                            $n_description_pro_rol = "";
                                        }

                                        $rolFunciones = $rol->rolRolFunction()->where('id_rol', $id_rol)->get();

                                        foreach ($rolFunciones as $rolFuncion) {

                                            $id_function = $rolFuncion->id_function;

                                            $UserFunctiones = UserFunction::where('id', $id_function)->get();

                                            foreach ($UserFunctiones as $UserFunction) {

                                                $FuncionesUsuario =  array(
                                                    'n_function_name'         => $UserFunction->n_function_name,
                                                    'n_description_function'  => $UserFunction->n_description,
                                                );

                                                array_push($ArrFuncionesUsuario, $FuncionesUsuario);
                                            }
                                        }
                                    } else {
                                        $id_rol                = "";
                                        $id_profile            = "";
                                        $n_profile_name_pro    = "";
                                        $n_description_pro     = "";
                                        $n_profile_name_rol    = "";
                                        $n_description_pro_rol = "";
                                    }

                                    $ProfilesUsuario =  array(
                                        'id_persona'           => $this->decodeUuid($userPersonaProfile->id_persona),
                                        'id_r_profile_rol'     => $id_r_profile_rolDecodificado,
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
                                    //$status = $userPersonaProfile->status; // mandar status del usuario
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

                                    $data_registro = "ID DEL USUARIO:" . " - " .$USER_ID . " - " .  "TOKEN DEL USUARIO:"  . " - " .$token   . " - " .  "VALIDACIÓN DEL PROCESO:" . " - " . $validar ;
                                    
                                    RegisterLog::createLogs("Logueo","Inicio de Sesión", $USER_ID,  $data_registro ,$fecha_registro, $id_local, $ip_remote, $ip_user);

                                    return response()->json(
                                        array(
                                            'resultado'      => true,
                                            'id_usuario'     => $USER_ID,
                                            'objeto_usuario' => $objetoProfilesUsuario
                                        ),
                                        200
                                    );
                                } else {
                                    $objetoProfilesUsuario = [];
                                    return response()->json(
                                        array(
                                            'resultado'      => false,
                                            'id_usuario'     => $USER_ID,
                                            'objeto_usuario' => $objetoProfilesUsuario
                                        ),
                                        400
                                    );
                                }
                            } else {
                                $validar = -5;
                                return response()->json(
                                    [
                                        'resultado' => false,
                                        'mensaje' => "No se encontró funciones asignadas al usuario logueado"
                                    ]
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
    }

}
