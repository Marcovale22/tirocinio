<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodotto extends Model
{
    protected $table = 'prodotti';

    protected $fillable = [
        'nome', 'tipo',  'prezzo','disponibilita','immagine', 
    ];

    public function vino()
    {
        return $this->hasOne(Vino::class, 'prodotto_id');
    }

    public function evento()
    {
        return $this->hasOne(Evento::class, 'prodotto_id');
    }
}

