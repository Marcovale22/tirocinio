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
                'bottiglie_stimate' => 100,
                'tipo_vino' => 'Bianco',
                'fase_produzione' => 'potatura',
                'immagine' => 'vigneto_colline.png',
                'visibile' => true
            
            ],
            [
                'nome' => 'Vigneto Tramonto Rosso',
                'descrizione' => 'Terreno argilloso, ideale per rossi strutturati.',
                'disponibilita' => 8,
                'prezzo_annuo' => 950.00,
                'bottiglie_stimate' => 100,
                'tipo_vino' => 'Rosso',
                'fase_produzione' => 'potatura',
                'immagine' => 'vigneto_tramonto.png',
                'visibile' => true
            ],
            [
                'nome' => 'Vigneto Colle Rosato',
                'descrizione' => 'Vigneto collinare dedicato alla produzione di rosati equilibrati e raffinati, con note floreali e una spiccata freschezza.',
                'disponibilita' => 5,
                'prezzo_annuo' => 1100.00,
                'bottiglie_stimate' => 100,
                'tipo_vino' => 'Rosato',
                'fase_produzione' => 'potatura',
                'immagine' => 'Vigneto_tre.jpg',
                'visibile' => true
            ],
        ];

        foreach ($vigneti as $data) {
            Vigneto::create($data);
        }
    }
}
