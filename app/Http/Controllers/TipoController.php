<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Tipo;
use App\Http\Resources\Tipo as TipoResource;

class TipoController extends Controller
{
 public function index()
    {
        return TipoResource::collection(Tipo::all());
    }

  
    public function store(Request $request)
    {
        //se for put pega registro, senao instacia
        $dado = $request->isMethod('put') ? Tipo::findOrFail($request->id) : new Tipo;
        
        $dado->nomeFantasia = $request->input('nomeFantasia');
        

        if($dado->save()){
            return new TipoResource($dado);
        }
        

    }

  
    public function show($id)
    {
        $dado = Tipo::findOrFail($id);
        return new TipoResource($dado);
    }


    public function destroy($id)
    {
        $dado = Tipo::findOrFail($id);
        if($dado->delete()){
            return new TipoResource($dado);
        }
    }
}