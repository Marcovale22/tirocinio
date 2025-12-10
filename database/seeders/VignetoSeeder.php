<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vigneto;

class VignetoSeeder extends Seeder
{
    public function run(): void
    {
        $vigneti = [
            [
                'nome' => 'Vigneto Colline Dorate',
                'descrizione' => 'Filari esposti a sud, perfetti per vini bianchi freschi.',
                'disponibilita' => 10,
                'prezzo_annuo' => 800.00,
                'immagine' => 'placeholder_vigneto.png',
            ],
            [
                'nome' => 'Vigneto Tramonto Rosso',
                'descrizione' => 'Terreno argilloso, ideale per rossi strutturati.',
                'disponibilita' => 8,
                'prezzo_annuo' => 950.00,
                'immagine' => 'placeholder_vigneto.png',
            ],
            [
                'nome' => 'Vigneto Brezza di Mare',
                'descrizione' => 'Vigneto vicino alla costa, con microclima unico.',
                'disponibilita' => 5,
                'prezzo_annuo' => 1100.00,
                'immagine' => 'placeholder_vigneto.png',
            ],
        ];

        foreach ($vigneti as $data) {
            Vigneto::create($data);
        }
    }
}
