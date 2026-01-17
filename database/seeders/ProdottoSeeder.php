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
                'disponibilita' => 10,
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
            'immagine' => 'evento_apertura.png',
            'disponibilita' => 40,
        ]);

        Evento::create([
            'prodotto_id' => $eventoProdotto->id,
            'data_evento' => '2026-05-25',
            'ora_evento' => '19:00:00',
            'luogo' => 'Monsampietro(AP)',
            'descrizione' => 'evnto di apertura della pagina web',
            
        ]);


        /*
        |--------------------------------------------------------------------------
        | 3) SEZIONE MERCH
        |--------------------------------------------------------------------------
        */

        Prodotto::create([
            'nome' => "T-shirt Vinicola Premium Bianca",
            'tipo' => 'merch',
            'prezzo' => 22.00,
            'immagine' => 'T-shirt_bianca.png',
            'disponibilita' => 10,
        ]);

         Prodotto::create([
            'nome' => "T-shirt Vinicola Premium Nera",
            'tipo' => 'merch',
            'prezzo' => 22.00,
            'immagine' => 'T-shirt_nera.png',
            'disponibilita' => 10,
        ]);

        Prodotto::create([
            'nome' => "cappello brendizzato",
            'tipo' => 'merch',
            'prezzo' => 10.00,
            'immagine' => 'cappello.jpeg',
            'disponibilita' => 10,
        ]);
    }
}
