<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\activarContoller;
use App\Http\Controllers\singupContoller;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FeederController;

Route::get('activar/{user}', [activarContoller::class, 'activar'])->name('activar')->middleware('signed');
Route::post('reactivate', [activarContoller::class, 'reactivar']);
Route::post('login', [singupContoller::class, 'login']);
Route::post('register', [singupContoller::class, 'register']);
Route::get('prueba', [activarContoller::class, 'prueba']);

Route::middleware('auth:sanctum')->group(function () {
    //user
    Route::put('user/update', [AuthController::class, 'updateUser']);
    Route::get('user/logout', [AuthController::class, 'logout']);
    Route::get('user/me', [AuthController::class, 'me']);


    //comederos
    Route::post('comederos/crear', [FeederController::class, 'crearComedero']);
    Route::get('comederos', [FeederController::class, 'obtenerComederos']);

});

use App\Http\Controllers\AdafruitController;

Route::get('/sync-feed/{feed}', [AdafruitController::class, 'syncFeed']);