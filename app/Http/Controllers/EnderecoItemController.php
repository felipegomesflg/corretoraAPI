<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\EnderecoItem;
use App\Http\Resources\EnderecoItem as EnderecoItemResource;

class EnderecoItemController extends Controller
{
    public function show($id)
    {
        $dado = EnderecoItem::findOrFail($id);
        return new EnderecoItemResource($dado);
    }

    public function findByEnderecoID($id)
    {
        $dado = EnderecoItem::where('enderecoid',$id)->get();
        return new EnderecoItemResource($dado);
    }
}
