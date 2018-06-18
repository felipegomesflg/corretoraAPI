<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\ContaItem;
use App\Http\Resources\ContaItem as ContaItemResource;

class ContaItemController extends Controller
{
    public function show($id)
    {
        $dado = ContaItem::findOrFail($id);
        return new ContaItemResource($dado);
    }

    public function findByContaID($id)
    {
        $dado = ContaItem::where('contaid',$id)->get();
        return new ContaItemResource($dado);
    }
}
