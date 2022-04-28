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

class AuthController extends Controller
{
    
    private static $messages = [
        'required' => 'El campo : token es obligatorio.'
    ];

    public function authenticateLogin(Request $request) {


        if($request->isJson()){   
         
        
        $validaToken =null; 

        try {

            $request->validate(
                [
                    'token' => 'required'
                ],
                self::$messages
            );
        
            $token = $request->input('token');
            Filtrar($token, "STRING");     

            Log::error('Incicia el proceso de recuperación de funciones y perfiles para el usuario '. $token);
            
            if (empty($token)) {
                return response()->json(
                    [
                        'resultado' => false,
                        'mensaje' => "El campo 'TOKER' es requerido."
                    ]
                );
            }
            
           
            
       
          // $UserAttribute = DB::connection('mysql3')->select("select odi.f_get_id_person('$token')");   
           $UserAttribute = UserAttribute::where('USER_ID', $token)->first();


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

                        $arrayIdfuncionesUsuario[]=[];

                        $USER_ID = 'de34df7b-c700-11ec-8f74-0242ac120002';//  = $UserAttribute->USER_ID;

                        dd($USER_ID, "entro al binario");

                        //$UserOdiPerfiles = DB::connection('mysql3')->select("call sp_get_functions_by_user('$token')"); 
                        $UserPersonaProfile = PersonaProfile::where('id_persona', $USER_ID)->first();
                       // $UserPersonaProfile = PersonaProfile::where('id_persona', $USER_ID)->with('recibeProfileRolPer')->get();
                       // $UserPersonaProfile = PersonaProfile::where('id_persona', $USER_ID)->recibeProfileRolPer();
                        
                       dd($UserPersonaProfile, "persona profile");

                        $UserOdiPerfiles = Profile::where('id', $USER_ID)->first();

                        dd($UserOdiPerfiles, "entro al else");

                       //if ($UserOdiPerfiles->count()) { // se activa al poner en funcion el select de SP (evaluar si los SP generan count)

                            foreach ($UserOdiPerfiles as $UserOdiPerfil) {

                                dd("Dentro del Foraech", $UserOdiPerfil);
                            
                                $status = $UserOdiPerfil->status;
                                
                                    if($status == StatusUsuario::INACTIVO){
                    
                                        return response()->json(
                                            [
                                                'resultado' => false,
                                                'mensaje' => "El usuario que intenta ingresar, se encuentra inactivo."
                                            ]
                                        );
                                    }

                                

                                    $arrayIdfuncionesUsuario[] = [
                                        "",
                                        ""
                                    ];
                                
                                return response()->json(
                                    array(
                                        'resultado'      => true,
                                        'email'          => $UserOdiPerfil->email,
                                        'user_id'        => $UserOdiPerfil->name,
                                        'user_jerarquia' => $UserOdiPerfil->password,
                                        'id_funciones'   => $arrayIdfuncionesUsuario
                                    ),
                                    200
                                );
                            }
                         /* } else {
                            return response()->json(
                                [
                                    'resultado' => false,
                                    'mensaje' => "No se encontró funciones asignadas al usuario logueado"
                                ]
                            );
                        } */
                    }
                
            } else  {
                Log::error('No se encontró información del usuario que intenta loguearse');
                return response()->json(
                    [
                        'resultado' => false,
                        'mensaje' => "No se encontró información del usuario que intenta loguearse"
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
