<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prodotto;
use App\Models\Vino;
use App\Models\Evento;

class ProdottoSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1) SEZIONE VINI
        |--------------------------------------------------------------------------
        */
        $vini = [
            [
                'nome' => "Festa dell'Ascencio",
                'annata' => 2025,
                'gradazione' => 13.0,
                'formato' => '0.75L',
                'prezzo' => 15.50,
                'immagine' => 'festa_dell_ascencio.png',
            ],
            [
                'nome' => "Da Sole",
                'annata' => 2025,
                'gradazione' => 12.5,
                'formato' => '0.75L',
                'prezzo' => 13.00,
                'immagine' => 'da_sole.png',
            ],
            [
                'nome' => "D'avora",
                'annata' => 2025,
                'gradazione' => 13.5,
                'formato' => '0.75L',
                'prezzo' => 18.00,
                'immagine' => 'd_avora.png',
            ],
        ];

        foreach ($vini as $vinoData) {

            // CREO IL PRODOTTO
            $prodotto = Prodotto::create([
                'nome' => $vinoData['nome'],
                'tipo' => 'vino',
                'prezzo' => $vinoData['prezzo'],
                'immagine' => $vinoData['immagine'],
            ]);

            // CREO IL RECORD SPECIFICO NELLA TABELLA VINI
            Vino::create([
                'prodotto_id' => $prodotto->id,
                'annata' => $vinoData['annata'],
                'gradazione' => $vinoData['gradazione'],
                'formato' => $vinoData['formato'],
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 2) SEZIONE EVENTO
        |--------------------------------------------------------------------------
        */

        $eventoProdotto = Prodotto::create([
            'nome' => "Degustazione d'Autunno",
            'tipo' => 'evento',
            'prezzo' => 35.00, // prezzo del biglietto
            'immagine' => 'evento_autunno.png',
        ]);

        Evento::create([
            'prodotto_id' => $eventoProdotto->id,
            'data_evento' => '2026-10-15',
            'ora_evento' => '18:00:00',
            'disponibilita' => 40,
        ]);


        /*
        |--------------------------------------------------------------------------
        | 3) SEZIONE MERCH
        |--------------------------------------------------------------------------
        */

        Prodotto::create([
            'nome' => "T-shirt Vinicola Premium",
            'tipo' => 'merch',
            'prezzo' => 22.00,
            'immagine' => 'tshirt_vinicola.png',
        ]);
    }
}
