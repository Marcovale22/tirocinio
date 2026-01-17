<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RichiestaVigneto extends Model
{
    protected $table = 'richieste_vigneto';

    protected $fillable = [
        'user_id',
        'vigneto_id',
        'annata',
        'stato',
        'prezzo_annuo',
        'bottiglie_stimate',
        'note_utente',
        'note_staff',
        'confermato_at',
    ];

    protected $casts = [
        'confermato_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vigneto(): BelongsTo
    {
        return $this->belongsTo(Vigneto::class, 'vigneto_id');
    }
}
