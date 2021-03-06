<?php

use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImagemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::apiResource('produto', ProdutoController::class);
Route::post('imagens/{produto}',[ImagemController::class, 'store']);
Route::delete('imagens/{produto}',[ImagemController::class, 'destroy']);

Route::post('login',[ AuthController::class, 'login']);
Route::post('logout',[ AuthController::class, 'logout']);
Route::post('refresh',[ AuthController::class, 'refresh']);

