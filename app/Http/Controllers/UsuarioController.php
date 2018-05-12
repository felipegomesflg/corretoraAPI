<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Usuario;
use App\Contato;
use App\ContatoItem;
use App\Http\Resources\Usuario as UsuarioResource;

class UsuarioController extends Controller
{
 public function index()
    {
        $dado = Usuario::all();
        
        foreach($dado as $item){
            $item->contato = ContatoItem::where('contatoid',$item->contatoid)->get();
        }
        return new UsuarioResource($dado);
        
    }

  
    public function store(Request $request)
    {
        //se for put pega registro, senao instacia
        $dado = $request->isMethod('put') ? Usuario::findOrFail($request->id) : new Usuario;
        
        $dado->id = $request->input('id');
        $dado->nome = $request->input('nome');
        $dado->email = $request->input('email');
        $dado->senha = $request->input('senha');
        $dado->foto = $request->input('foto');
        $dado->cpf = $request->input('cpf');
        $dado->cor = $request->input('cor');
        $dado->menu = $request->input('menu');
        $dado->ativo = $request->input('ativo');
        $dado->tipoid = $request->input('tipoid');
        $dado->empresaid = $request->input('empresaid');
        
        //se for put usa o contatoid
        $dado->contatoid = $request->isMethod('put') ? $request->input('contatoid') : 0;
        //se for put pega registro, senao instacia
        $contato = $request->isMethod('put') ? Contato::findOrFail($request['contatoid']) : new Contato;
        $contato->nome = 'Usuario';
        $contato->save();
        
        //caso seja put apaga todos contatos vindos do banco
        if($request->isMethod('put')){
            ContatoItem::where('contatoid',$contato->id)->delete();
        }
        //lista contatos vindos do objeto para adiciona-los
        foreach($request->contato as $item){
            $contatoItem = new ContatoItem;
            $contatoItem->nome = $item['nome'];
            $contatoItem->email = $item['email'];
            $contatoItem->telefone = $item['telefone'];
            $contatoItem->contatoid = $contato->id;
            $contatoItem->save();
        }

        $dado->contatoid = $contato->id; //seta contatoid vindo do post/put do model Contato
        
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