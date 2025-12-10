<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rifornimento;

class StaffRifornimentiController extends Controller
{
  public function store(Request $request)
    {
        $validated = $request->validate([
            'prodotto_id' => 'required|exists:prodotti,id',
            'quantita'    => 'required|integer|min:1',
        ], [
            'quantita.required' => 'La quantità è obbligatoria.',
            'quantita.integer'  => 'La quantità deve essere un numero intero.',
            'quantita.min'      => 'La quantità deve essere almeno 1.',
        ]);

        
        $user = $request->user(); 

        if (!$user) {
            return back()->with('error', 'Utente non autenticato.');
        }

        Rifornimento::create([
            'prodotto_id' => $validated['prodotto_id'],
            'user_id'     => $user->id,
            'quantita'    => $validated['quantita'],
            'stato'       => 'in_lavorazione',
        ]);

        return back()->with('success', 'Richiesta di rifornimento inviata all\'amministratore.');
    }
}
