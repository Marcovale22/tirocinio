<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RichiestaVigneto;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vigneto extends Model
{
    protected $table = 'vigneti';

   protected $fillable = [
        'nome',
        'descrizione',
        'disponibilita',
        'prezzo_annuo',
        'immagine',
        'visibile',
        'bottiglie_stimate',
        'tipo_vino',
        'fase_produzione',
    ];

    public function richieste(): HasMany
    {
        return $this->hasMany(RichiestaVigneto::class, 'vigneto_id');
    }
    
}
