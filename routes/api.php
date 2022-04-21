<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserRegisterController; 
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::controller(UserRegisterController::class)->group(function(){
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});



Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [UserRegisterController::class, 'logout']);
    Route::get('/consulta-user', [UserRegisterController::class, 'consultaUser']);
    Route::post('/authenticateLogin', [AuthController::class, 'authenticateLogin']);
    
});

