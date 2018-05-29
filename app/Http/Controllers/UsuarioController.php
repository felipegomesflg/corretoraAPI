<?php

namespace App\Http\Controllers;

use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Usuario;
use App\AcaoTipo;
use App\Contato;
use App\ContatoItem;
use App\Enderecos;
use App\EnderecoItems;
use App\Contas;
use App\ContaItems;
use App\Http\Resources\Usuario as UsuarioResource;
use Intervention\Image\ImageManagerStatic as Image;



class UsuarioController extends Controller
{
 public function index()
    {
        $dado = Usuario::where('ativo',true)->get();

        foreach($dado as $item){
            $item->contato = ContatoItem::where('contatoid',$item->contatoid)->get();
            $item->endereco = EnderecoItems::where('enderecoid',$item->enderecoid)->get();
            $item->conta = ContaItems::where('contaid',$item->contaid)->join('bancos','bancos.id','=','conta_items.bancoid')->get();
        }
        return new UsuarioResource($dado);
        
    }

  
    public function store(Request $request)
    {
        //se for put pega registro, senao instacia
        $dado = $request->isMethod('put') ? Usuario::findOrFail($request->id) : new Usuario;
        
        $dado->id = $request->input('id');
        $dado->nome = $request->input('nome');
        $dado->usuario = $request->input('usuario');
        $dado->senha = $request->input('senha');
        $dado->cpf = $request->input('cpf');
        $dado->cor = $request->input('cor');
        $dado->menu = $request->input('menu');
        $dado->ativo = $request->input('ativo');
        $dado->tipoid = $request->input('tipoid');
        $dado->api_token = $request->input('tipoid');
        $dado->empresaid = $request->input('empresaid');

        if(strlen($request->foto)>200){
            $dado->foto = $request->input('foto');
            $path = $request->isMethod('put')? Usuario::findOrFail($request->id)->foto : "" ;
            if($path =='')
            $path ='img/usuario-'.time().".png";
            Image::make(file_get_contents($dado->foto))->resize(200, 200)->save($path);    
            $dado->foto = $path;
        }
        
        $dado->contatoid = $request->isMethod('put') ? $request->input('contatoid') : 0;
        $contato = $request->isMethod('put') ? Contato::findOrFail($request['enderecoid']) : new Contato;
        $contato->nome = 'Usuario';
        $contato->save();
        
        $dado->enderecoid = $request->isMethod('put') ? $request->input('enderecoid') : 0;
        $endereco = $request->isMethod('put') ? Enderecos::findOrFail($request['enderecoid']) : new Enderecos;
        $endereco->nome = 'Usuario';
        $endereco->save();
        
        $dado->contaid = $request->isMethod('put') ? $request->input('contaid') : 0;
        $conta = $request->isMethod('put') ? Contas::findOrFail($request['contaid']) : new Contas;
        $conta->nome = 'Usuario';
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
            return new UsuarioResource($dado);
        }

    }

    public function preferencia(Request $request)
    {
        $dado = Usuario::findOrFail($request->id);
        $dado->cor = $request->cor;
        $dado->menu = $request->menu;

        if(strlen($request->foto)>200){
            $dado->foto = $request->input('foto');
            $path = $request->isMethod('put')? Usuario::findOrFail($request->id)->foto : "" ;
            if($path =='')
            $path ='img/usuario-'.time().".png";
            Image::make(file_get_contents($dado->foto))->resize(200, 200)->save($path);    
            $dado->foto = $path;
        }else
            $dado->foto = $request->foto;

        if($dado->save()){
            return new UsuarioResource($dado);
        }
    }
  
    public function show($id)
    {
        $dado = Usuario::findOrFail($id);
        return new UsuarioResource($dado);
    }


    public function destroy(Request $request)
    {
        $dado = Usuario::findOrFail($request->id);
        $dado->ativo = false;
        //if($dado->delete()){
        if($dado->save()){
            return new UsuarioResource($dado);
        }
    }

  public function login(Request $request){
      
        $dado = Usuario::where([
            ['usuario','=',$request->usuario],['senha','=',$request->senha],['usuarios.ativo','=',true]
            ])->select('usuarios.cpf','usuarios.usuario','usuarios.id','usuarios.nome','usuarios.foto','usuarios.cor','usuarios.menu','usuarios.api_token','usuarios.empresaid','usuarios.tipoid',
            'empresas.razaoSocial','empresas.logo','empresas.padrao','empresas.cor as empresacor','empresas.menu as empresamenu','tipos.nome as tipo')
            ->join('empresas','usuarios.empresaid','=','empresas.id')
            ->join('tipos','usuarios.tipoid','=','tipos.id')->first();
                 
            if($dado->padrao==1){
                $dado->cor = $dado->empresacor;
                $dado->menu = $dado->menucor;
            }
        return $dado;
  }
  public function auth($id){
    $usuario = Usuario::findOrFail($id)->select('tipoid')->first();
    $acesso =[];
    $acaotipo = AcaoTipo::where('tipoid',$usuario->tipoid)->select('acaoid','ver','criar','editar','apagar')->get();
    foreach($acaotipo as $item){
        $acesso[$item->acaoid] = $item;
    }
    return $acesso;
  }
}