<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vino extends Model
{
    protected $table = 'vini';

    protected $fillable = ['prodotto_id', 'annata', 'solfiti', 'gradazione', 'formato'];

    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class);
    }

    public function eventi()
    {
        return $this->belongsToMany(Evento::class, 'evento_vini')
            ->withPivot('quantita')
            ->withTimestamps();
    }
}

