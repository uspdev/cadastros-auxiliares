<?php

use App\Http\Controllers\MensagemController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('mensagens', MensagemController::class)
    ->except(['show'])
    ->parameters(['mensagens' => 'mensagem'])
    ->middleware(['auth', 'can:admin']);

// Permite usar Gate::check('user')na view 404
Route::fallback(function(){
    return view('errors.404');
 });
