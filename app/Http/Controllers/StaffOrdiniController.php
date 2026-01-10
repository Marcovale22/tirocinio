<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffOrdiniController extends Controller
{
    public function index()
    {
        $ordini = DB::table('ordini')
            ->join('users', 'ordini.user_id', '=', 'users.id')
            ->select(
                'ordini.*',
                'users.username as username',
                'users.email as email'
            )
            ->orderByDesc('ordini.created_at')
            ->get();


        // righe ordine (con nome prodotto)
        $items = DB::table('ordine_items')
            ->join('prodotti', 'ordine_items.prodotto_id', '=', 'prodotti.id')
            ->select(
                'ordine_items.ordine_id',
                'ordine_items.prodotto_id',
                'prodotti.nome as prodotto_nome',
                'ordine_items.quantita',
                'ordine_items.subtotale'
            )
            ->whereIn('ordine_items.ordine_id', $ordini->pluck('id'))
            ->get()
            ->groupBy('ordine_id');

        return view('staff.ordini', compact('ordini', 'items'));
    }

    public function aggiornaStato(Request $request, $ordine)
    {
        $validated = $request->validate([
            'stato' => 'required|in:in_attesa,confermato,spedito,consegnato,annullato'
        ]);

        return DB::transaction(function () use ($ordine, $validated) {

            // lock ordine
            $o = DB::table('ordini')->where('id', $ordine)->lockForUpdate()->first();
            if (!$o) return back()->with('error', 'Ordine non trovato.');

            $nuovoStato = $validated['stato'];
            $statoAttuale = $o->stato;

            // blocco modifiche inutili
            if ($statoAttuale === $nuovoStato) {
                return back()->with('success', 'Stato già impostato.');
            }

            // se è già annullato, non permetto di tornare indietro (evita bug stock)
            if ($statoAttuale === 'annullato') {
                return back()->with('error', 'Un ordine annullato non può essere modificato.');
            }

            if (in_array($statoAttuale, ['spedito','consegnato']) && $nuovoStato === 'annullato') {
                return back()->with('error', 'Non puoi annullare un ordine già spedito/consegnato.');
            }            

            // se sto annullando: ripristino stock
            if ($nuovoStato === 'annullato') {
                $righe = DB::table('ordine_items')
                    ->where('ordine_id', $ordine)
                    ->get();

                foreach ($righe as $r) {
                    // lock prodotto e ripristina disponibilità
                    $p = DB::table('prodotti')->where('id', $r->prodotto_id)->lockForUpdate()->first();
                    if ($p) {
                        DB::table('prodotti')->where('id', $r->prodotto_id)->update([
                            'disponibilita' => ((int)($p->disponibilita ?? 0)) + (int)$r->quantita
                        ]);
                    }
                }
            }

            // aggiorno stato ordine
            DB::table('ordini')->where('id', $ordine)->update([
                'stato' => $nuovoStato,
                'updated_at' => now()
            ]);

            return back()->with('success', 'Stato ordine aggiornato correttamente.');
        });
    }
}

