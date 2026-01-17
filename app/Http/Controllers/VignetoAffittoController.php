<?php

namespace App\Http\Controllers;


use App\Models\Vigneto;
use App\Models\RichiestaVigneto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VignetoAffittoController extends Controller
{
   
    public function show(Vigneto $vigneto)
    {
        return view('utente.dettaglio_vigneti', compact('vigneto'));
    }

     public function store(Request $request, Vigneto $vigneto)
    {
        $data = $request->validate([
            'annata' => ['required', 'integer', 'min:2000', 'max:2100'],
        ]);

        if (($vigneto->disponibilita ?? 0) <= 0) {
            return back()->with('error', 'Vigneto non disponibile.');
        }

        $userId = Auth::id();
        $annata = (int) $data['annata'];

        return DB::transaction(function () use ($vigneto, $userId, $annata) {

            
            $richiesta = RichiestaVigneto::where('user_id', $userId)
                ->where('vigneto_id', $vigneto->id)
                ->where('annata', $annata)
                ->lockForUpdate()
                ->first();

            if ($richiesta) {
                // se è già attiva o confermata, non puoi reinviare
                if (in_array($richiesta->stato, ['in_attesa', 'confermato'])) {
                    return back()->with('error', 'Hai già inviato una richiesta per questo vigneto.');
                }

                // se era annullata o rifiutata -> la reinvio (niente INSERT, quindi niente errore UNIQUE)
                $richiesta->update([
                    'stato' => 'in_attesa',
                    'prezzo_annuo' => $vigneto->prezzo_annuo,
                    'bottiglie_stimate' => $vigneto->bottiglie_stimate,
                    'confermato_at' => null,
                ]);

                return back()->with('success', 'Richiesta reinviata! In attesa di conferma dallo staff.');
            }

            // se non esiste, la creo
            try {
                RichiestaVigneto::create([
                    'user_id' => $userId,
                    'vigneto_id' => $vigneto->id,
                    'annata' => $annata,
                    'stato' => 'in_attesa',
                    'prezzo_annuo' => $vigneto->prezzo_annuo,
                    'bottiglie_stimate' => $vigneto->bottiglie_stimate,
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                // doppio click / race condition
                if (($e->errorInfo[1] ?? null) == 1062) {
                    return back()->with('error', 'Hai già inviato una richiesta per questo vigneto.');
                }
                throw $e;
            }

            return back()->with('success', 'Richiesta inviata! In attesa di conferma dallo staff.');
        });
    }


    public function mieiVigneti()
    {
        $richieste = RichiestaVigneto::with('vigneto')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('utente.miei_vigneti', compact('richieste'));
    }

    public function annulla(RichiestaVigneto $richiesta)
{
    // Solo il proprietario può annullare
    if ($richiesta->user_id !== Auth::id()) {
        abort(403);
    }

    // Solo se è ancora in attesa
    if ($richiesta->stato !== 'in_attesa') {
        return back()->with('error', 'Puoi annullare solo richieste in attesa.');
    }

    $richiesta->update([
        'stato' => 'annullato',
    ]);

    return back()->with('success', 'Richiesta annullata correttamente.');
}
}
