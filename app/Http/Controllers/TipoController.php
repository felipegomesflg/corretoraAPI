<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Tipo;
use App\AcaoTipo;
use App\Http\Resources\Tipo as TipoResource;

class TipoController extends Controller
{
 public function index()
    {
        $dado = Tipo::all();
        foreach($dado as $item){
            $acao =[];
            foreach(AcaoTipo::where('tipoid',$item->id)->get() as $a){
                array_push($acao ,$a->acaoid);
            }
            $item->acao = $acao;
        }
        return new TipoResource($dado);
        
    }

  
    public function store(Request $request)
    {
        //se for put pega registro, senao instacia
        $dado = $request->isMethod('put') ? Tipo::findOrFail($request->id) : new Tipo;
        $dado->nome = $request->input('nome');

        //caso seja put apaga todos acessos vindos do banco
        if($request->isMethod('put')){
            AcaoTipo::where('tipoid',$request->id)->delete();
        }

        if($dado->save()){
            foreach($request->acao as $item){//adiciona novas açoes levando id do tipo inserido/editado 
                $acao = new AcaoTipo;
                $acao->tipoid = $dado->id;
                $acao->acaoid = $item;
                $acao->save();
            }
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