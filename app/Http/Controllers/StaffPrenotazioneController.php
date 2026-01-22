<?php

namespace App\Http\Controllers;

use App\Models\Prenotazione;
use Illuminate\Http\Request;

class StaffPrenotazioneController extends Controller
{
    public function index(Request $request)
    {
        $query = Prenotazione::query()
            ->with(['utente', 'evento.prodotto'])
            ->orderByDesc('created_at');

        // (opzionale) filtri rapidi
        if ($request->filled('stato')) {
            $query->where('stato', $request->stato);
        }

        $prenotazioni = $query->get();

        return view('staff.prenotazioni_staff', compact('prenotazioni'));
    }

    public function updateStato(Request $request, Prenotazione $prenotazione)
    {
        $data = $request->validate([
            'stato' => ['required', 'in:in_attesa,confermata,annullata'],
        ]);

        $prenotazione->update([
            'stato' => $data['stato'],
        ]);

        return back()->with('success', 'Stato prenotazione aggiornato.');
    }
}
