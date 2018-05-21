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
use App\Http\Resources\Usuario as UsuarioResource;
use Intervention\Image\ImageManagerStatic as Image;



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

    public function preferencia(Request $request)
    {
        $dado = Usuario::findOrFail($request->id);
        $dado->cor = $request->cor;
        $dado->menu = $request->menu;
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

  public function login(Request $request){
        $dado = Usuario::where([
            ['usuario','=',$request->usuario],['senha','=',$request->senha],['usuarios.ativo','=',true]
            ])->select('usuarios.cpf','usuarios.id','usuarios.nome','usuarios.foto','usuarios.cor','usuarios.menu','usuarios.api_token','usuarios.empresaid','usuarios.tipoid',
            'empresas.razaoSocial','empresas.logo','empresas.padrao','empresas.cor as empresacor','empresas.menu as empresamenu','tipos.nome as tipo')
            ->join('empresas','usuarios.empresaid','=','empresas.id')
            ->join('tipos','usuarios.tipoid','=','tipos.id')->first();
            
            if($dado->padrao==1){
                $dado->cor = $dado->empresacor;
                $dado->menu = $dado->menucor;
            }
        if($dado){
            $acesso =[];
            $acaotipo = AcaoTipo::where('tipoid',$dado->tipoid)->get();
            foreach($acaotipo as $item){
                array_push($acesso ,$item->acaoid);
            }
            $dado->acesso = $acesso;
        }
        return $dado;
  }
}