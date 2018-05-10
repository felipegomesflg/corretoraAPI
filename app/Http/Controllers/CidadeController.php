<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Cidade;
use App\Http\Resources\Cidade as CidadeResource;

class CidadeController extends Controller
{
 public function index()
    {
        return CidadeResource::collection(Cidade::all());
    }

  
    public function store(Request $request)
    {
        //se for put pega registro, senao instacia
        $dado = $request->isMethod('put') ? Cidade::findOrFail($request->id) : new Cidade;
        
        $dado->nomeFantasia = $request->input('nomeFantasia');
        

        if($dado->save()){
            return new CidadeResource($dado);
        }
        

    }

  
    public function show($id)
    {
        $dado = Cidade::findOrFail($id);
        return new CidadeResource($dado);
    }

    public function selectByUf($id)
    {
        return CidadeResource::collection(Cidade::where('Uf',$id)->get());
    }


    public function destroy($id)
    {
        $dado = Cidade::findOrFail($id);
        if($dado->delete()){
            return new CidadeResource($dado);
        }
    }
}