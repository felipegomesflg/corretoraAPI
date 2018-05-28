<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Acao;
use App\Http\Resources\Acao as AcaoResource;

class AcaoController extends Controller
{
 public function index()
    {
        return AcaoResource::collection(Acao::where('ativo',true)->get());
    }

  
    public function store(Request $request)
    {
        //se for put pega registro, senao instacia
        $dado = $request->isMethod('put') ? Acao::findOrFail($request->id) : new Acao;
        
        $dado->nomeFantasia = $request->input('nomeFantasia');
        

        if($dado->save()){
            return new AcaoResource($dado);
        }
        

    }

  
    public function show($id)
    {
        $dado = Acao::findOrFail($id);
        return new AcaoResource($dado);
    }


    public function destroy($id)
    {
        $dado = Acao::findOrFail($id);
        $dado->ativo = false;
        if($dado->save()){
            return new AcaoResource($dado);
        }
    }
}