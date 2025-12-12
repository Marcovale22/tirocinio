<?php

namespace App\Http\Controllers;

use App\Models\Rifornimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminRifornimentoController extends Controller
{
     public function getRifornimenti()
    {
        // Solo rifornimenti in lavorazione
        $rifornimenti = Rifornimento::with(['prodotto', 'user'])
            ->where('stato', 'in_lavorazione')
            ->get();

        return view('admin.rifornimenti', compact('rifornimenti'));
    }

    public function getStoricoRifornimenti()
{
    $rifornimenti = Rifornimento::with(['prodotto', 'user'])
        ->whereIn('stato', ['confermato', 'annullato'])
        ->orderByDesc('updated_at')
        ->get();

    return view('admin.storicoRifornimenti', compact('rifornimenti'));
}

    public function aggiornaStato(Request $request, Rifornimento $rifornimento)
    {
        // accettiamo solo rifornimenti ancora in lavorazione
        if ($rifornimento->stato !== 'in_lavorazione') {
            return redirect()
                ->route('admin.rifornimenti.getRifornimenti')
                ->with('error', 'Questa richiesta di rifornimento è già stata gestita.');
        }

        $data = $request->validate([
            'azione' => 'required|in:conferma,annulla',
        ]);

        try {
            DB::transaction(function () use ($data, $rifornimento) {

                if ($data['azione'] === 'conferma') {
                    // 1) aumento la disponibilità del prodotto
                    $prodotto = $rifornimento->prodotto;

                    if ($prodotto) {
                        // se è null lo tratto come 0
                        $prodotto->disponibilita = ($prodotto->disponibilita ?? 0) + $rifornimento->quantita;
                        $prodotto->save();
                    }

                    // 2) aggiorno lo stato del rifornimento
                    $rifornimento->stato = 'confermato';
                    $rifornimento->save();

                } else {
                    // azione = annulla -> cambio solo lo stato del rifornimento
                    $rifornimento->stato = 'annullato';
                    $rifornimento->save();
                }
            });

        } catch (\Throwable $e) {
            // in caso di errore DB, torno con messaggio
            return redirect()
                ->route('admin.rifornimenti.getRifornimenti')
                ->with('error', 'Si è verificato un errore durante l\'aggiornamento del rifornimento.');
        }

        $msg = $data['azione'] === 'conferma'
            ? 'Rifornimento confermato e disponibilità aggiornata.'
            : 'Rifornimento annullato correttamente.';

        return redirect()
            ->route('admin.rifornimenti.getRifornimenti')
            ->with('success', $msg);
    }

}
