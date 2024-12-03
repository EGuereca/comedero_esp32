<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\activarContoller;
use App\Http\Controllers\singupContoller;

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

Route::get('activar/{user}', [activarContoller::class, 'activar'])->name('activar')->middleware('signed');
Route::post('reactivate', [activarContoller::class, 'reactivar']);
Route::post('login', [singupContoller::class, 'login']);
Route::post('register', [singupContoller::class, 'register']);
Route::get('prueba', [activarContoller::class, 'prueba']);
