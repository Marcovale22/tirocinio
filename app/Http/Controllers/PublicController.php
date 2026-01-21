<?php

namespace App\Http\Controllers;

use App\Models\Prodotto;
use App\Models\Vino;
use App\Models\Evento;
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

    public function vinoDettaglio($vino) 
    {
        $prodotto = Prodotto::query()
            ->whereKey($vino)
            ->where('tipo', 'vino')
            ->with('vino')           // relazione Prodotto -> hasOne(Vino)
            ->firstOrFail();

        return view('utente.dettaglio_vini', [
            'prodotto' => $prodotto,
            'vino'     => $prodotto->vino,
        ]);
    }

    public function tenute(){
        return view('publica.tenute');
    }

    public function affittaVigneto(){
        
        $vigneti = Vigneto::where('visibile',true)
                    ->get();

        return view('publica.affitta',compact('vigneti'));
    }

    public function show(Vigneto $vigneto)
    {
        return view('utente.dettaglio_vigneti', compact('vigneto'));
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

    public function eventoDettaglio($evento)
    {
        $prodotto = Prodotto::query()
            ->whereKey($evento)
            ->where('tipo', 'evento')
            ->with([
                'evento' => function ($q) {
                    $q->with([
                        'vini' => function ($q2) {
                            $q2->wherePivot('quantita', '>', 0)
                            ->join('prodotti', 'vini.prodotto_id', '=', 'prodotti.id')
                            ->orderBy('prodotti.nome')
                            ->select('vini.*')           // importantissimo
                            ->with('prodotto');          // per stampare nome/immagine/prezzo
                        }
                    ]);
                }
            ])
            ->firstOrFail();

        return view('utente.dettaglio_eventi', [
            'prodotto' => $prodotto,
            'evento'   => $prodotto->evento,
            'vini'     => $prodotto->evento->vini,
        ]);
    }

}
