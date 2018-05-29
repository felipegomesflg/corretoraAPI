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
        $dado = Tipo::where('ativo',true)->get();;
        foreach($dado as $item){
            $acao =[];
            foreach(AcaoTipo::where('tipoid',$item->id)->get() as $a){
                $acao[$a->acaoid] = [$a->ver,$a->criar,$a->editar,$a->apagar];
                //array_push($acao ,{$a->acaoid : });
            }
            $acao = json_encode($acao);
            $acao = json_decode($acao);
            $item->acao = $acao;
        }
        return new TipoResource($dado);
        
    }

  
    public function store(Request $request)
    {
        //se for put pega registro, senao instacia
        $dado = $request->isMethod('put') ? Tipo::findOrFail($request->id) : new Tipo;
        $dado->nome = $request->input('nome');
        $dado->ativo = $request->input('ativo');

        //caso seja put apaga todos acessos vindos do banco
        if($request->isMethod('put')){
            AcaoTipo::where('tipoid',$request->id)->delete();
        }
        
        if($dado->save()){
            foreach($request->acao as $key=>$item){//adiciona novas açoes levando id do tipo inserido/editado 
                $acao = new AcaoTipo;
                $acao->tipoid = $dado->id;
                $acao->acaoid = $key;
                $acao->ver = $item[0];
                $acao->criar = $item[1];
                $acao->editar = $item[2];
                $acao->apagar = $item[3];
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


    public function destroy(Request $request)
    {
        $dado = Tipo::findOrFail($request->id);
        $dado->ativo = false;
        //if($dado->delete()){
        if($dado->save()){
            return new TipoResource($dado);
        }
    }
}