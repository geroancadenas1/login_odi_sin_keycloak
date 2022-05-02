<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enums\StatusUsuario;
use App\Models\Keycloak\UserAttribute;
use App\Models\Login\Profile;
use App\Models\Login\PersonaProfile;
use App\Models\Login\UserFunction;
use App\Models\Login\RolFunction;
use App\Models\Login\ProfileRol;
use App\Models\Login\Rol;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;



class AuthController extends Controller
{

    public static function encodeUuid($uuid): string
    {
        if (! Uuid::isValid($uuid)) {
            return $uuid;
        }
        if (! $uuid instanceof Uuid) {
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

    public function authenticateLogin(Request $request) {


        if($request->isJson()){   
                
        $validaToken = null; 
        $validar     = 0;
        $mensaje     = "";
        $ArrProfilesUsuario = array();
        $ArrFuncionesUsuario = array();

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

                Log::error('Incicia el proceso de recuperación de funciones y perfiles para el usuario '. $token);
                
                    if (empty($token)) {
                        $validar = -1;
                        return response()->json(
                            [
                                'resultado' => false,
                                'mensaje' => "El campo 'TOKER' es requerido."
                            ]
                        );
                    }
    
                    if ($validar >= 0) {
                        $UserAttribute = UserAttribute::where('USER_ID', $token)->first();

                        if ($UserAttribute == null) {
                            $validar = -2;
                            $mensaje = "No se encontró información del usuario que intenta loguearse";
                        } else {
                            // mandar a buscar en los mails, validarv tabla con samuel
                            // $UserAttribute=true;
                        }
                    }

                    if ($UserAttribute) { 
                    
                            $validaToken = $UserAttribute->USER_ID; 
                            
                            
                            if ($validaToken == null) {
                                return response()->json(
                                    [
                                        'resultado' => false,
                                        'mensaje' => "El token que intenta registrarse, no es válido"
                                    ]
                                );
                            } else {

                            $USER_ID ='d5eb6a0d-c6f2-11ec-8f74-0242ac120002' ;//  = $UserAttribute->USER_ID;
                            $isUuid = Str::isUuid('de34df7b-c700-11ec-8f74-0242ac120002');
                            $id_persona = $this->encodeUuid($USER_ID);

                             
                            $userPersonaProfiles = PersonaProfile::where('id_persona',$id_persona)->get(); // get porque hay muchos id personas con varios id profile rol
                             
                                if ($userPersonaProfiles->count()) { 
                                   
                                    foreach ($userPersonaProfiles as $userPersonaProfile) {

                                        $id_r_profile_rol = $userPersonaProfile->id_r_profile_rol;
                                        $id_r_profile_rolDecodificado = $this->decodeUuid($id_r_profile_rol);

                                        $profileRol = $userPersonaProfile->recibeProfileRolPer()->where('id', $id_r_profile_rol)->first();  // un first porque solo hay un id_profile_rol para varias personas

                                        if($profileRol) {
                                            $id_rol = $profileRol['id_rol'];
                                            $id_profile = $profileRol['id_profiles'];

                                            $profile = Profile::where('id', $id_profile )->first();
                                                if($profile) { $n_profile_name_pro = $profile->n_profile_name; $n_description_pro  = $profile->n_description; } else { $n_profile_name_pro  =""; $n_description_pro = "";}
	                                        $rol = Rol::where('id', $id_rol)->first();
                                                if($rol) { $n_profile_name_rol = $rol->n_profile_name; $n_description_pro_rol = $rol->n_description;} else { $n_profile_name_rol = ""; $n_description_pro_rol = "";}

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
                                    
                                    $objetoProfilesUsuario= [
                                        'persona_profile' => $ArrProfilesUsuario,
                                        'tipo' => "Objeto de profile, roles y funciones",
                                    ];

                                    //$status = $userPersonaProfile->status; // mandar status del usuario
                                    $status = 1;
                                    
                                        if($status == StatusUsuario::INACTIVO){
                                            return response()->json(
                                                [
                                                    'resultado' => false,
                                                    'mensaje' => "El usuario que intenta ingresar, se encuentra inactivo."
                                                ]
                                            );
                                        }

                                        
                                    return response()->json(
                                        array(
                                            'resultado'      => true,
                                            'id_usuario'     => $USER_ID,
                                            'objeto_usuario' => $objetoProfilesUsuario
                                        ),
                                        200
                                    );
                                    
                                } else {
                                    return response()->json(
                                        [
                                            'resultado' => false,
                                            'mensaje' => "No se encontró funciones asignadas al usuario logueado"
                                        ]
                                    );
                                } 
                            }
                        
                    } else  {
                        Log::error('No se encontró información del usuario que intenta loguearse');
                        return response()->json(
                            [
                                'resultado' => false,
                                'mensaje' => $mensaje
                            ]
                        );
                    }
            } catch (Exception $ex) {
                Log::error('Ha ocurrido un error al obtener los perfiles del usuario '. $ex);
                
                return response()->json(
                    [
                        'resultado' => false,
                        'mensaje' =>  $ex  
                    ]
                );
            }

        } else {  
            
            Log::error('Proceso no atorizado, debe enviar los datos en un formato autorizado. ');

            return response()->json(
                [ 'resultado' => false,
                    'mensaje' =>'Proceso no atorizado, debe enviar los datos en un formato autorizado. '
                ],
                401, []); 
            }
    }

}
