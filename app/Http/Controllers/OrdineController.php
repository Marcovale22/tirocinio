<?php

namespace App\Http\Controllers;

use App\Models\Prodotto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrdineController extends Controller
{
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (count($cart) === 0) {
            return back()->with('error', 'Errore nell\'ordine.');
        }

        $userId = Auth::id();

        return DB::transaction(function () use ($cart, $userId) {
            $ids = array_keys($cart);

            $prodotti = Prodotto::whereIn('id', $ids)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $totale = 0;

            // 1) controlli disponibilità e totale
            foreach ($cart as $pid => $row) {
                $p = $prodotti->get($pid);
                if (!$p) {
                    return back()->with('error', 'Prodotto non trovato.');
                }

                $q = (int)$row['quantita'];
                $disp = (int)($p->disponibilita ?? 0);

                if ($q > $disp) {
                    return back()->with('error', "Disponibilità insufficiente per {$p->nome}.");
                }

                $totale += $q * (float)$p->prezzo;
            }
            
            

            // 2) crea ordine
            $ordineId = DB::table('ordini')->insertGetId([
                'user_id' => $userId,
                'totale' => $totale,
                'stato' => 'in_attesa', // oppure 'confermato'
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3) righe + scala stock
            foreach ($cart as $pid => $row) {
                $p = $prodotti->get($pid);
                $q = (int)$row['quantita'];
                $prezzo = (float)$p->prezzo;

                DB::table('ordine_items')->insert([
                    'ordine_id' => $ordineId,
                    'prodotto_id' => $pid,
                    'quantita' => $q,
                    'prezzo_unitario' => $prezzo,
                    'subtotale' => $q * $prezzo,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('prodotti')->where('id', $pid)->update([
                    'disponibilita' => $p->disponibilita - $q
                ]);
            }

            // 4) svuota carrello
            session()->forget('cart');

            return redirect()->route('utente.carrello.index')
                ->with('success', 'Ordine inviato correttamente.');
        });
    }

    public function mieiOrdini()
{
    $ordini = DB::table('ordini')
        ->where('user_id', Auth::id())
        ->orderByDesc('created_at')
        ->get();

    // Prendo tutte le righe e le raggruppo per ordine_id
    $items = DB::table('ordine_items')
        ->join('prodotti', 'ordine_items.prodotto_id', '=', 'prodotti.id')
        ->select(
            'ordine_items.ordine_id',
            'ordine_items.quantita',
            'ordine_items.prezzo_unitario',
            'ordine_items.subtotale',
            'prodotti.nome as prodotto_nome'
        )
        ->whereIn('ordine_items.ordine_id', $ordini->pluck('id'))
        ->get()
        ->groupBy('ordine_id');

    return view('utente.ordini', compact('ordini', 'items'));
}

}
