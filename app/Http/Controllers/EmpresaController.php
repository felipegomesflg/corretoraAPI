<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Empresa;
use App\Contato;
use App\ContatoItem;
use App\Http\Resources\Empresa as EmpresaResource;
use Intervention\Image\ImageManagerStatic as Image;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empresas = Empresa::all();
        foreach($empresas as $empresa){
            $empresa->contato = ContatoItem::where('contatoid',$empresa->contatoid)->get();
        }
        return new EmpresaResource($empresa);
    }

  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //se for put pega registro, senao instacia
        $empresa = $request->isMethod('put') ? Empresa::findOrFail($request->id) : new Empresa;
        
        
        $empresa->id = $request->input('id');
        $empresa->cnpj = $request->input('cnpj');
        $empresa->razaoSocial = $request->input('razaoSocial');
        $empresa->nomeFantasia = $request->input('nomeFantasia');
        $empresa->cep = $request->input('cep');
        $empresa->endereco = $request->input('endereco');
        $empresa->complemento = $request->input('complemento');
        $empresa->numero = $request->input('numero');
        $empresa->estado = $request->input('estado');
        $empresa->cidade = $request->input('cidade');
        $empresa->ativo = $request->input('ativo');
        $empresa->cor = $request->input('cor');
        $empresa->menu = $request->input('menu');
        $empresa->padrao = $request->input('padrao');
        $empresa->logo = $request->input('logo');

        
        if($empresa->logo){
            $path = $request->isMethod('put')? Empresa::findOrFail($request->id)->logo : "" ;
            if($path =='')
            $path ='img/empresa-'.time().".png";
            Image::make(file_get_contents($empresa->logo))->save($path);    
            $empresa->logo = $path;
        }
        //se for put usa o contatoid
        $empresa->contatoid = $request->isMethod('put') ? $request->input('contatoid') : 0;
        //se for put pega registro, senao instacia
        $contato = $request->isMethod('put') ? Contato::findOrFail($request['contatoid']) : new Contato;
        $contato->nome = 'Empresa';
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

        $empresa->contatoid = $contato->id; //seta contatoid vindo do post/put do model Contato
        
        if($empresa->save()){
            return new EmpresaResource($empresa);
        }
        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->contato = ContatoItem::where('contatoid',$empresa->contatoid)->get();
        return new EmpresaResource($empresa);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $empresa = Empresa::findOrFail($id);
        if($empresa->delete()){
            return new EmpresaResource($empresa);
        }
    }
}
