<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdsController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register',                [AuthController::class, 'register']);
Route::post('/login',                   [AuthController::class, 'login']);

Route::resource('/ads',                 AdsController::class);

Route::middleware('auth:api')->group(function(){
    Route::get('/user',                 [AdsController::class, 'getUser']);

    Route::put('/ads/{ad}/extend',      [AdsController::class, 'extend']);
    Route::post('/ads/{ad}/rate',       [AdsController::class, 'rate']);

    Route::post('/logout',              [AuthController::class, 'logout']);
});