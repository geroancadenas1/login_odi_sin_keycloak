<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enums\StatusUsuario;
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
         
        
        $validaToken = false; 

        try {

            $request->validate(
                [
                    'token' => 'required'
                ],
                self::$messages
            );
        
            $token = $request->input('token');
            Filtrar($token, "STRING");     
            
            if (empty($token)) {
                return response()->json(
                    [
                        'resultado' => false,
                        'mensaje' => "El campo 'TOKER' es requerido."
                    ]
                );
            }
            
           

       
           $PerfilesUsers = DB::connection('mysql3')->select("select odi.f_get_id_person('$token')");   
           //$PerfilesUsers = DB::connection('mysql2')->table('CLIENT')->where('ID','f58ed2f3-eda1-4440-bfef-f254936e83e0')->get();  

           dd("Luego del Query", $PerfilesUsers);

            if ($PerfilesUsers) { //(evaluar si los SP generan count)

                foreach ($PerfilesUsers as $PerfilesUs) {
                   

                    //$validaToken = $PerfilesUs->resultado; // cambiar por valor del resultado real. 
                    $validaToken = true; 


                    if ($validaToken == false) {
                        return response()->json(
                            [
                                'resultado' => false,
                                'mensaje' => "El token que intenta registrarse, no es válido"
                            ]
                        );
                    } else {

                        $arrayIdfuncionesUsuario[]=[];

                        

                        $UserOdiPerfiles = DB::connection('mysql3')->select("call sp_get_functions_by_user('$token')"); 
                        //$UserOdiPerfiles = DB::connection('mysql3')->table('T_Function')->where('N_description','Ingreso de Centros')->first();

                        dd($UserOdiPerfiles);

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
                } 
            } else  {
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
        return response()->json(
            [ 'resultado' => false,
                'mensaje' =>'Proceso no atorizado, debe enviar los datos en un formato autorizado. '
            ],
            401, []); 
        }

    }

}
