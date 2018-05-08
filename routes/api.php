<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('empresas', 'EmpresaController@index');

Route::get('empresa/{id}', 'EmpresaController@show');

Route::post('empresa', 'EmpresaController@store');

Route::put('empresa', 'EmpresaController@store');

Route::delete('empresa', 'EmpresaController@destroy');


Route::get('contatos', 'ContatoController@index');

Route::get('contato/{id}', 'ContatoController@show');

Route::post('contato', 'ContatoController@store');

Route::put('contato', 'ContatoController@store');

Route::delete('contatos', 'ContatoController@destroy');

Route::group(['prefix'=>'empresas'],function(){
	Route::apiResource('/{empresa}/contatos','ContatoController');
});