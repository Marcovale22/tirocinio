<?php

namespace Database\Seeders;

use App\Models\Prodotto;
use App\Models\Vino;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProdottoSeeder extends Seeder
{
    public function run(): void
    {
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

            // 1) Creo il prodotto "generico"
            $prodotto = Prodotto::create([
                'nome' => $vinoData['nome'],
                'tipo' => 'vino',
                'prezzo' => $vinoData['prezzo'],
                'immagine' => $vinoData['immagine'], // qui poi metterai il path della foto
            ]);

            // 2) Creo il record specifico in "vini" collegato al prodotto
            Vino::create([
                'prodotto_id' => $prodotto->id,
                'annata' => $vinoData['annata'],
                'gradazione' => $vinoData['gradazione'],
                'formato' => $vinoData['formato'],
            ]);
        }
    }
}

