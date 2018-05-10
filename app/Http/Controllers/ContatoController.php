<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Contato;
use App\Http\Resources\Contato as ContatoResource;

class ContatoController extends Controller
{
 public function index()
    {
        return ContatoResource::collection(Contato::all());
    }

  
    public function store(Request $request)
    {
        //se for put pega registro, senao instacia
        $dado = $request->isMethod('put') ? Contato::findOrFail($request->id) : new Contato;
        
        $dado->nome = $request->input('nome');
        

        if($dado->save()){
            return new ContatoResource($dado);
        }
        

    }

  
    public function show($id)
    {
        $dado = Contato::findOrFail($id);
        return new ContatoResource($dado);
    }


    public function destroy($id)
    {
        $dado = Contato::findOrFail($id);
        if($dado->delete()){
            return new ContatoResource($dado);
        }
    }
}