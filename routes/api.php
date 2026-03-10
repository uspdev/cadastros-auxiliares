<?php

use App\Http\Controllers\ApiMensagemController;
use App\Http\Controllers\ApiPosProgramaController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['web', 'api.password'])->get('/mensagens', [ApiMensagemController::class, 'index']);
Route::middleware(['web', 'api.password'])->get('/pos/programas', [ApiPosProgramaController::class, 'index']);
Route::middleware(['web', 'api.password'])->get('/pos/programas/{codcur}', [ApiPosProgramaController::class, 'show'])
    ->whereNumber('codcur');
