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
    
            // lock evento + prodotto (disponibilità sta nel prodotto)
            $eventoLocked   = Evento::whereKey($evento->id)->lockForUpdate()->firstOrFail();
            $prodottoLocked = Prodotto::whereKey($eventoLocked->prodotto_id)->lockForUpdate()->firstOrFail();
    
            // lock eventuale prenotazione esistente per (user,event)
            $prenEsistente = Prenotazione::where('user_id', Auth::id())
                ->where('evento_id', $eventoLocked->id)
                ->lockForUpdate()
                ->first();
    
            // se esiste ed è attiva -> blocca
            if ($prenEsistente && in_array($prenEsistente->stato, ['in_attesa', 'confermata'])) {
                return back()->with('error', 'Hai già una prenotazione attiva per questo evento.');
            }
    
            // controllo disponibilità sul prodotto
            if ($prodottoLocked->disponibilita < $posti) {
                return back()->with('error', 'Posti insufficienti. Disponibili: ' . $prodottoLocked->disponibilita);
            }
    
            // se esiste ed è annullata -> RIATTIVA aggiornando posti e stato
            if ($prenEsistente && $prenEsistente->stato === 'annullata') {
                $prenEsistente->update([
                    'posti' => $posti,       // <-- aggiorna anche se quantità diversa
                    'stato' => 'in_attesa',
                ]);
            } else {
                // altrimenti crea nuova prenotazione
                Prenotazione::create([
                    'user_id'   => Auth::id(),
                    'evento_id' => $eventoLocked->id,
                    'posti'     => $posti,
                    'stato'     => 'in_attesa',
                ]);
            }
    
            // scala disponibilità
            $prodottoLocked->decrement('disponibilita', $posti);
    
            return redirect()
                ->route('utente.prenotazioni.index')
                ->with('success', 'Prenotazione creata con successo!');
        });
    }
    

    public function annulla(Prenotazione $prenotazione)
    {
        // sicurezza: l'utente può annullare solo le sue prenotazioni
        if ($prenotazione->user_id !== Auth::id()) {
            abort(403);
        }

        return DB::transaction(function () use ($prenotazione) {

            // lock della prenotazione
            $pren = Prenotazione::whereKey($prenotazione->id)->lockForUpdate()->firstOrFail();

            // se già annullata, niente
            if ($pren->stato === 'annullata') {
                return back()->with('error', 'La prenotazione è già stata annullata.');
            }

            // lock evento
            $evento = Evento::whereKey($pren->evento_id)->lockForUpdate()->firstOrFail();

            // lock prodotto dell'evento (dove sta la disponibilità)
            $prodotto = Prodotto::whereKey($evento->prodotto_id)->lockForUpdate()->firstOrFail();

            // restituisci posti
            $prodotto->increment('disponibilita', $pren->posti);

            // aggiorna stato
            $pren->update(['stato' => 'annullata']);

            return redirect()
                ->route('utente.prenotazioni.index')
                ->with('success', 'Prenotazione annullata con successo.');
        });
    }
}
