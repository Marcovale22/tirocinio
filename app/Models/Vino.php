<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vino extends Model
{
    protected $table = 'vini';

    protected $fillable = ['prodotto_id', 'disponibilita', 'annata', 'solfiti', 'gradazione', 'formato'];

    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class);
    }
}

