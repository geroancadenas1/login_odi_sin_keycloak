<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Exception;


class UserRegisterController extends BaseController
{
    public function register(Request $request)
    {
       
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

       
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create([
            'name'     => $input['name'],
            'email'    => $input['email'],
            'status'   => '1',
            'password' => $input['password'],
        ]);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;
          
       return $this->sendResponse($success, 'Usuario registrado correctamente.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

     
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
                     
            $user = User::where('email', $request->email)->first();
        
            $success['token'] = $user->createToken('MyApp')->plainTextToken; 
            $success['name'] =  $user->name;
   
            return $this->sendResponse($success, 'Usuario logueado correctamente');
        } 
        else{ 
         
           return $this->sendError('Usuario no se pudo loguear correctamente', ['error'=>'Usuario no autorizado']);
        } 
    }

    

    public function logout()
    {
        
        Auth::user()->tokens->each(function($token, $key) {
            $token->delete();
        });
        
        return [
            'message' => 'Usuario desconectado'
        ];
    }

    public function consultaUser()
    {
        try{
            return response()->json([
                'status' => 'Acción con éxito',
                'user' => Auth::user(),
            ]);
        } catch (Exception $ex) {
            Log::error('Ha ocurrido un error al obtener los perfiles del usuario '. $ex);
            return response()->json(
                [
                    'resultado' => false,
                    'mensaje' =>  $ex  
                ]
            );
        }
        
    }


}
