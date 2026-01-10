<?php

namespace App\Http\Controllers;

use App\Models\Prodotto;
use App\Models\Vigneto;
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
        
        $vigneti = Vigneto::where('visibile',true)
                    ->get();

        return view('publica.affitta',compact('vigneti'));
    }

    public function shop(){

    $vini = Prodotto::with('vino')
            ->where('tipo', 'vino')
            ->get();

    // Recupero merch (prodotti senza tabella specifica)
    $merch = Prodotto::where('tipo', 'merch')->get();

    // Recupero eventi (prodotti con tabella evento)
    $eventi = Prodotto::with('evento')
        ->where('tipo', 'evento')
        ->get();


    // Ritorno la vista catalogo con tutte le variabili
    return view('publica.shop', compact('vini', 'merch', 'eventi'));
    }
}
