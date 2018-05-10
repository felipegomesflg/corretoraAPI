<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\AcaoTipo;
use App\Http\Resources\AcaoTipo as AcaoTipoResource;

class AcaoTipoController extends Controller
{
 public function index()
    {
        return AcaoTipoResource::collection(AcaoTipo::all());
    }

  
    public function store(Request $request)
    {
        //se for put pega registro, senao instacia
        $dado = $request->isMethod('put') ? AcaoTipo::findOrFail($request->id) : new AcaoTipo;
        
        $dado->nomeFantasia = $request->input('nomeFantasia');
        

        if($dado->save()){
            return new AcaoTipoResource($dado);
        }
        

    }

  
    public function show($id)
    {
        $dado = AcaoTipo::findOrFail($id);
        return new AcaoTipoResource($dado);
    }


    public function destroy($id)
    {
        $dado = AcaoTipo::findOrFail($id);
        if($dado->delete()){
            return new AcaoTipoResource($dado);
        }
    }
}