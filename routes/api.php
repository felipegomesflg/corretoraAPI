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


Route::get('empresas/isNew', 'EmpresaController@isNew');
Route::get('empresas', 'EmpresaController@index');
Route::get('empresa/{id}', 'EmpresaController@show');
Route::post('empresa', 'EmpresaController@store');
Route::put('empresa', 'EmpresaController@store');
Route::delete('empresa', 'EmpresaController@destroy');

Route::get('contatos', 'ContatoController@index');
Route::get('contato/{id}', 'ContatoController@show');
Route::post('contato', 'ContatoController@store');
Route::put('contato', 'ContatoController@store');
Route::delete('contato', 'ContatoController@destroy');

Route::get('tipos', 'TipoController@index');
Route::get('tipo/{id}', 'TipoController@show');
Route::post('tipo', 'TipoController@store');
Route::put('tipo', 'TipoController@store');
Route::delete('tipo', 'TipoController@destroy');

Route::get('acaos', 'AcaoController@index');
Route::get('acao/{id}', 'AcaoController@show');
Route::post('acao', 'AcaoController@store');
Route::put('acao', 'AcaoController@store');
Route::delete('acao', 'AcaoController@destroy');

Route::get('acaotipos', 'AcaoTipoController@index');
Route::get('acaotipo/{id}', 'AcaoTipoController@show');
Route::post('acaotipo', 'AcaoTipoController@store');
Route::put('acaotipo', 'AcaoTipoController@store');
Route::delete('acaotipo', 'AcaoTipoController@destroy');

Route::get('usuarios', 'UsuarioController@index');
Route::get('usuario/{id}', 'UsuarioController@show');
Route::get('oauth/{id}', 'UsuarioController@auth');
Route::post('usuario', 'UsuarioController@store');
Route::put('usuario', 'UsuarioController@store');
Route::put('usuario/preferencia', 'UsuarioController@preferencia');
Route::delete('usuario', 'UsuarioController@destroy');
Route::post('oauth/login', 'UsuarioController@login');

Route::get('contatoitem/findbycontatoid/{id}', 'ContatoItemController@findByContatoID');
Route::get('enderecoitem/findbyenderecoid/{id}', 'EnderecoItemController@findByEnderecoID');
Route::get('contaitem/findbycontaid/{id}', 'ContaItemController@findByContaID');


Route::get('clientes', 'ClienteController@index');
Route::get('cliente/select', 'ClienteController@select');
Route::get('cliente/{id}', 'ClienteController@show');
Route::post('cliente', 'ClienteController@store');
Route::put('cliente', 'ClienteController@store');
Route::put('cliente/preferencia', 'ClienteController@preferencia');
Route::delete('cliente', 'ClienteController@destroy');

Route::get('bancos', 'BancoController@index');

Route::get('estados', 'EstadoController@index');
Route::get('estado/{id}', 'EstadoController@show');
Route::post('estado', 'EstadoController@store');
Route::put('estado', 'EstadoController@store');
Route::delete('estado', 'EstadoController@destroy');

Route::get('cidades', 'CidadeController@index');
Route::get('cidade/{id}', 'CidadeController@show');
Route::get('cidade/selectByUf/{id}', 'CidadeController@selectByUf');
Route::post('cidade', 'CidadeController@store');
Route::put('cidade', 'CidadeController@store');
Route::delete('cidade', 'CidadeController@destroy');


