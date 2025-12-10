<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Prodotto;
use App\Models\Vigneto;

class StaffCatalogoController extends Controller
{
    public function getCatalogo()
    {
        $vini = Prodotto::with('vino')
                ->where('tipo', 'vino')
                ->get();

            // Recupero merch (prodotti senza tabella specifica)
            $merch = Prodotto::where('tipo', 'merch')->get();

            // Recupero eventi (prodotti con tabella evento)
            $eventi = Prodotto::with('evento')
                ->where('tipo', 'evento')
                ->get();

            // Recupero vigneti (tabella dedicata)
            $vigneti = Vigneto::all();

            // Ritorno la vista catalogo con tutte le variabili
            return view('staff.catalogo', compact('vini', 'merch', 'eventi', 'vigneti'));
    }
}
