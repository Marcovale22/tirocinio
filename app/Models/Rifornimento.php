<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rifornimento extends Model
{
    protected $table = 'rifornimenti';

    protected $fillable = [
        'prodotto_id',
        'user_id',
        'quantita',
        'stato',
        'note',
    ];

    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

