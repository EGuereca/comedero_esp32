<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\activarContoller;
use App\Http\Controllers\SingupController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FeederController;

Route::get('activar/{user}', [activarContoller::class, 'activar'])->name('activar')->middleware('signed');
Route::post('reactivate', [activarContoller::class, 'reactivar']);
Route::post('login', [SingupController::class, 'login']);
Route::post('register', [SingupController::class, 'register']);
Route::get('prueba', [activarContoller::class, 'prueba']);

Route::middleware('auth:sanctum')->group(function () {
    //user
    Route::put('user/update', [AuthController::class, 'updateUser']);
    Route::get('user/logout', [AuthController::class, 'logout']);
    Route::get('user/me', [AuthController::class, 'me']);


    //comederos
    Route::post('/mascotas/crear', [FeederController::class, 'crearMascota']);
    Route::get('/mascotas', [FeederController::class, 'verMascotas']);
    Route::post('/comederos/crear', [FeederController::class, 'crearComedero']);
    Route::get('/comederos', [FeederController::class, 'verComederos']);
    Route::get('/comedero/{id}', [FeederController::class, 'verComedero']);
    
});

use App\Http\Controllers\AdafruitController;

Route::get('/sync-feed/{feed}', [AdafruitController::class, 'syncFeed']);

Route::post('/comedores', [FeederController::class, 'crearComedor'])->middleware('auth:sanctum');
