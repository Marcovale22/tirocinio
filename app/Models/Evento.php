<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $table = 'eventi';

    protected $fillable = ['prodotto_id', 'data_evento', 'ora_evento','disponibilita'];

    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class);
    }
}

