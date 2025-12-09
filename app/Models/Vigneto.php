<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vigneto extends Model
{
    protected $table = 'vigneti';

   protected $fillable = [
        'nome',
        'descrizione',
        'disponibilita',
        'prezzo_annuo',
        'immagine',
    ];
}
