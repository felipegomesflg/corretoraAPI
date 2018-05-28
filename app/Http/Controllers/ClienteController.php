<?php

namespace App\Http\Controllers;

use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Cliente;
use App\Contato;
use App\ContatoItem;
use App\Enderecos;
use App\EnderecoItems;
use App\Contas;
use App\ContaItems;
use App\Http\Resources\Cliente as ClienteResource;

class ClienteController extends Controller
{
    public function index()
    {
        $dado = Cliente::where('ativo',true)->get();

        foreach($dado as $item){
            $item->contato = ContatoItem::where('contatoid',$item->contatoid)->get();
            $item->endereco = EnderecoItems::where('enderecoid',$item->enderecoid)->get();
            $item->conta = ContaItems::where('contaid',$item->contaid)->join('bancos','bancos.id','=','conta_items.bancoid')->get();
        }
        return new ClienteResource($dado);
        
    }

  
    public function store(Request $request)
    {
        //se for put pega registro, senao instacia
        $dado = $request->isMethod('put') ? Cliente::findOrFail($request->id) : new Cliente;
      
        $dado->id = $request->input('id');
        $dado->nome = $request->input('nome');
        $dado->cpf = $request->input('cpf');
        $dado->rg = $request->input('rg');
        $dado->sexo = $request->input('sexo');
        $dado->profissao = $request->input('profissao');
        $dado->mae = $request->input('mae');
        $dado->pai = $request->input('pai');
        $dado->estadoCivil = $request->input('estadoCivil');
        $dado->observacao = $request->input('observacao');
        $dado->nascimento = $request->input('nascimento');
        $dado->ativo = $request->input('ativo');
        $dado->empresaid = $request->input('empresaid');

        $dado->contatoid = $request->isMethod('put') ? $request->input('contatoid') : 0;
        $contato = $request->isMethod('put') ? Contato::findOrFail($request['enderecoid']) : new Contato;
        $contato->nome = 'Cliente';
        $contato->save();
        
        $dado->enderecoid = $request->isMethod('put') ? $request->input('enderecoid') : 0;
        $endereco = $request->isMethod('put') ? Enderecos::findOrFail($request['enderecoid']) : new Enderecos;
        $endereco->nome = 'Cliente';
        $endereco->save();
        
        $dado->contaid = $request->isMethod('put') ? $request->input('contaid') : 0;
        $conta = $request->isMethod('put') ? Contas::findOrFail($request['contaid']) : new Contas;
        $conta->nome = 'Cliente';
        $conta->save();
        
        if($request->isMethod('put')){
            ContatoItem::where('contatoid',$contato->id)->delete();
            EnderecoItems::where('enderecoid',$endereco->id)->delete();
            ContaItems::where('contaid',$conta->id)->delete();
        }
        
        if(count($request->contato)>0){
            foreach($request->contato as $item){
                $contatoItem = new ContatoItem;
                $contatoItem->nome = $item['nome'];
                $contatoItem->email = $item['email'];
                $contatoItem->telefone = $item['telefone'];
                $contatoItem->contatoid = $contato->id;
                $contatoItem->save();
            }
        }

        if(count($request->endereco)>0){
            foreach($request->endereco as $item){
                $enderecoItem = new EnderecoItems;
                $enderecoItem->cep = $item['cep'];
                $enderecoItem->endereco = $item['endereco'];
                $enderecoItem->numero = $item['numero'];
                $enderecoItem->complemento = $item['complemento'];
                $enderecoItem->estado = $item['estado'];
                $enderecoItem->cidade = $item['cidade'];
                $enderecoItem->enderecoid = $endereco->id;
                $enderecoItem->save();
            }
        }
        if(count($request->conta)>0){
            foreach($request->conta as $item){
                $contatoItem = new ContaItems;
                $contatoItem->nome = $item['nome'];
                $contatoItem->cpf = $item['cpf'];
                $contatoItem->bancoid = $item['bancoid'];
                $contatoItem->agencia = $item['agencia'];
                $contatoItem->conta = $item['conta'];
                $contatoItem->contaid = $conta->id;
                $contatoItem->save();
            }
        }
        $dado->contatoid = $contato->id; 
        $dado->enderecoid = $endereco->id; 
        $dado->contaid = $conta->id; 
        if($dado->save()){
            return new ClienteResource($dado);
        }

    }

    public function show($id)
    {
        $dado = Cliente::findOrFail($id);
        return new ClienteResource($dado);
    }


    public function destroy(Request $request)
    {
        $dado = Cliente::findOrFail($request->id);
        $dado->ativo = false;
        //if($dado->delete()){
        if($dado->save()){
            return new ClienteResource($dado);
        }
    }
}
