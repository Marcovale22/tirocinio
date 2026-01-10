<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdineItem extends Model
{
    protected $table = 'ordine_items';

    protected $fillable = [
        'ordine_id',
        'prodotto_id',
        'quantita',
        'prezzo_unitario',
        'subtotale',
    ];

    public function ordine()
    {
        return $this->belongsTo(Ordine::class);
    }

    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class);
    }
}

