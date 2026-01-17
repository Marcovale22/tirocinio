<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\RichiestaVigneto;
use App\Models\Vigneto;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RichiestaVignetoStaffController extends Controller
{
    public function index()
    {
        $richieste = RichiestaVigneto::with(['user', 'vigneto'])
            ->orderByDesc('created_at')
            ->get();

        return view('staff.richieste_vigneto', compact('richieste'));
    }

    public function conferma(RichiestaVigneto $richiesta)
    {
        if ($richiesta->stato !== 'in_attesa') {
            return back()->with('error', 'La richiesta non è in attesa.');
        }

        return DB::transaction(function () use ($richiesta) {

            // lock vigneto per evitare race
            $vigneto = Vigneto::where('id', $richiesta->vigneto_id)->lockForUpdate()->first();

            if (($vigneto->disponibilita ?? 0) <= 0) {
                return back()->with('error', 'Vigneto non più disponibile.');
            }

            // conferma richiesta
            $richiesta->update([
                'stato' => 'confermato',
                'confermato_at' => now(),
            ]);

            // scala disponibilità di 1 lotto
            $vigneto->update([
                'disponibilita' => $vigneto->disponibilita - 1,
            ]);

            return back()->with('success', 'Richiesta confermata e disponibilità aggiornata.');
        });
    }

    public function rifiuta(RichiestaVigneto $richiesta)
    {
        if ($richiesta->stato !== 'in_attesa') {
            return back()->with('error', 'La richiesta non è in attesa.');
        }

        $richiesta->update(['stato' => 'rifiutato']);

        return back()->with('success', 'Richiesta rifiutata.');
    }

   
    
}

