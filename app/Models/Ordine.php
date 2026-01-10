<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordine extends Model
{
    protected $table = 'ordini';

    protected $fillable = [
        'user_id',
        'totale',
        'stato',
    ];

    /**
     * Un ordine appartiene a un utente
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Un ordine ha più righe
     */
    public function items()
    {
        return $this->hasMany(OrdineItem::class);
    }
}

