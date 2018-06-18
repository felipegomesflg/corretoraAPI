<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Requests;
use App\ContatoItem;
use App\Http\Resources\ContatoItem as ContatoItemResource;

class ContatoItemController extends Controller
{
   
   
    public function show($id)
    {
        $dado = ContatoItem::findOrFail($id);
        return new ContatoItemResource($dado);
    }

    public function findByContatoID($id)
    {
        $dado = ContatoItem::where('contatoid',$id)->get();
        return new ContatoItemResource($dado);
    }
   
}
