<?php

namespace App\Http\Controllers;
use App\Models\Evento;
use App\Models\Prenotazione;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Prodotto;
class PrenotazioniController extends Controller
{

    public function index()
    {
        $prenotazioni = Prenotazione::query()
            ->where('user_id', Auth::id())
            ->with([
                'evento.prodotto',         
                'evento.vini.prodotto',    
            ])
            ->orderByDesc('created_at')
            ->get();

        return view('utente.mie_prenotazioni', compact('prenotazioni'));
    }
    public function store(Request $request, Evento $evento)
    {
        $request->validate([
            'posti' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $posti = (int) $request->posti;

        return DB::transaction(function () use ($evento, $posti) {

            $eventoLocked   = Evento::whereKey($evento->id)->lockForUpdate()->firstOrFail();
            $prodottoLocked = Prodotto::whereKey($eventoLocked->prodotto_id)->lockForUpdate()->firstOrFail();

            $exists = Prenotazione::where('user_id', Auth::id())
                ->where('evento_id', $eventoLocked->id)
                ->whereIn('stato', ['in_attesa', 'confermata'])
                ->exists();

            if ($exists) {
                return back()->with('error', 'Hai già una prenotazione attiva per questo evento.');
            }

            if ($prodottoLocked->disponibilita < $posti) {
                return back()->with('error', 'Posti insufficienti. Disponibili: ' . $prodottoLocked->disponibilita);
            }

            $pren = Prenotazione::create([
                'user_id'   => Auth::id(),
                'evento_id' => $eventoLocked->id,
                'posti'     => $posti,
                'stato'     => 'in_attesa',
            ]);

            $prodottoLocked->decrement('disponibilita', $posti);

            // ✅ successo: vai a "le mie prenotazioni"
            return redirect()
                ->route('utente.prenotazioni.index')
                ->with('success', 'Prenotazione creata con successo!');
        });
    }
}
