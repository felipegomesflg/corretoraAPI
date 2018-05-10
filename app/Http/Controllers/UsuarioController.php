<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Usuario;
use App\Http\Resources\Usuario as UsuarioResource;

class UsuarioController extends Controller
{
 public function index()
    {
        return UsuarioResource::collection(Usuario::all());
    }

  
    public function store(Request $request)
    {
        //se for put pega registro, senao instacia
        $dado = $request->isMethod('put') ? Usuario::findOrFail($request->id) : new Usuario;
        
        $dado->nomeFantasia = $request->input('nomeFantasia');
        

        if($dado->save()){
            return new UsuarioResource($dado);
        }
        

    }

  
    public function show($id)
    {
        $dado = Usuario::findOrFail($id);
        return new UsuarioResource($dado);
    }


    public function destroy($id)
    {
        $dado = Usuario::findOrFail($id);
        if($dado->delete()){
            return new UsuarioResource($dado);
        }
    }
}