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
    Route::post('/users-register-odi', 'register');
    Route::post('/users-login-odi', 'login');
});

Route::post('/authenticate-odi', [AuthController::class, 'authenticateLogin']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/users-logout-odi', [UserRegisterController::class, 'logout']);
    Route::get('/users-consulta-odi', [UserRegisterController::class, 'consultaUser']);
});

