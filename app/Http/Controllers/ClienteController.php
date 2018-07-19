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
use App\EnderecoItem;
use App\Contas;
use App\ContaItem;
use App\Http\Resources\Cliente as ClienteResource;
use App\Http\Resources\ContatoItem as ContatoItemResource;

class ClienteController extends Controller
{
    public function index()
    {
        $dado = Cliente::where('ativo',true);
        return datatables($dado)
        ->addColumn('contato',function($dado){
            return ContatoItem::where('contatoid',$dado->contatoid)->get();
        })
        ->addColumn('endereco',function($dado){
            return EnderecoItem::where('enderecoid',$dado->enderecoid)->get();
        })
        ->addColumn('conta',function($dado){
            return ContaItem::where('contaid',$dado->contaid)->join('bancos','bancos.id','=','conta_items.bancoid')->get();
        })
        ->make(true);
        //return new ClienteResource($dado);

    }

    public function select(Request $request)
    {

        $term = trim($request->q);
        if (empty($term)) {
            return DB::table('clientes')->select('id','nome as text')->limit(5)->orderBy('nome')->get();
        }
        return Cliente::where('nome','like','%'.$term.'%')->select('id','nome as text')->limit(5)->orderBy('nome')->get();
    }


    public function store(Request $request)
    {
        //se for put pega registro, senao instacia
        $dado = $request->isMethod('put') ? Cliente::findOrFail($request->id) : new Cliente;

        $dado->id = $request->input('id');
        $dado->nome = $request->input('nome');
        $dado->cpf_cnpj = $request->input('cpf_cnpj');
        $dado->rg = $request->input('rg');
        $dado->rg_orgao = $request->input('rg_orgao');
        $dado->rg_data = $request->input('rg_data');
        $dado->sexo = $request->input('sexo');
        $dado->profissao = $request->input('profissao');
        $dado->mae = $request->input('mae');
        $dado->pai = $request->input('pai');
        $dado->maeID = $request->input('maeID');
        $dado->paiID = $request->input('paiID');
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
            EnderecoItem::where('enderecoid',$endereco->id)->delete();
            ContaItem::where('contaid',$conta->id)->delete();
        }

        if(count($request->contato)>0){
            foreach($request->contato as $item){
                $contatoItem = new ContatoItem;
                $contatoItem->nome = $item['nome'];
                $contatoItem->email = $item['email'];
                $contatoItem->telefone = $item['telefone'];
                $contatoItem->observacao = $item['observacao'];
                $contatoItem->contatoid = $contato->id;
                $contatoItem->save();
            }
        }

        if(count($request->endereco)>0){
            foreach($request->endereco as $item){
                $enderecoItem = new EnderecoItem;
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
                $contatoItem = new ContaItem;
                $contatoItem->nome = $item['nome'];
                $contatoItem->cpf = $item['cpf'];
                $contatoItem->bancoid = $item['bancoid'];
                $contatoItem->agencia = $item['agencia'];
                $contatoItem->conta = $item['conta'];
                $contatoItem->tipo = $item['tipo'];
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
