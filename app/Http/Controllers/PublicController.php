<?php

namespace App\Http\Controllers;

use App\Models\Prodotto;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function vini(){

        $vini = Prodotto::with('vino')
                ->where('tipo','vino')
                ->get();

        return view('publica.vini',compact('vini'));
    }

    public function tenute(){
        return view('publica.tenute');
    }

    public function affittaVigneto(){
        return view('publica.affitta');
    }

    public function shop(){
        return view('publica.shop');
    }
}
