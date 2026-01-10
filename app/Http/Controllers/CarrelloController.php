<?php

namespace App\Http\Controllers;

use App\Models\Prodotto;
use Illuminate\Http\Request;

class CarrelloController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        $totale = 0;
        foreach ($cart as $row) {
            $totale += ((float)$row['prezzo']) * ((int)$row['quantita']);
        }

        return view('utente.carrello', compact('cart', 'totale'));
    }

    public function add(Request $request, Prodotto $prodotto)
    {
        // opzionale: limita a vino + merch (se hai un campo tipo)
        // abort_unless(in_array($prodotto->tipo, ['vino','merch']), 404);

        if (((int)($prodotto->disponibilita ?? 0)) <= 0) {
            return back()->with('error', 'Prodotto non disponibile.');
        }

        $qty = max(1, (int)$request->input('quantita', 1));

        $cart = session()->get('cart', []);

        $tipo = $prodotto->tipo ?? '';
        $folder = 'prodotti';

        if ($tipo === 'vino')  $folder = 'vini';
        if ($tipo === 'merch') $folder = 'merch';

        if (isset($cart[$prodotto->id])) {
            $cart[$prodotto->id]['quantita'] += $qty;
        } else {
            $cart[$prodotto->id] = [
                'prodotto_id' => $prodotto->id,
                'nome' => $prodotto->nome,
                'prezzo' => (float) $prodotto->prezzo,
                'immagine' => $prodotto->immagine ?? 'placeholder.png',
                'quantita' => $qty,
                'folder' => $folder,    
                'tipo' => $tipo    
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Prodotto aggiunto al carrello.');
    }

    public function update(Request $request, Prodotto $prodotto)
    {
        $qty = max(1, (int)$request->input('quantita', 1));

        $cart = session()->get('cart', []);
        if (!isset($cart[$prodotto->id])) {
            return back();
        }

        $cart[$prodotto->id]['quantita'] = $qty;
        session()->put('cart', $cart);

        return back()->with('success', 'Quantità aggiornata.');
    }

    public function remove(Prodotto $prodotto)
    {
        $cart = session()->get('cart', []);
        unset($cart[$prodotto->id]);

        session()->put('cart', $cart);

        return back()->with('success', 'Prodotto rimosso dal carrello.');
    }
}
