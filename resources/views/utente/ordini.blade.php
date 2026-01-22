@extends('base')

@section('title', config('app.name') . ' - I miei ordini')

@section('content')
<div class="pagina-catalogo py-4">
    <div class="container">

        <div class="text-center mb-4">
            <h1 class="titolo-catalogo mb-0">I miei ordini</h1>
            <div class="sottotitolo-vigneti">
                Qui trovi tutti i tuoi ordini con lo stato di lavorazione.
            </div>
        </div>

        @if($ordini->count() === 0)
            <div style="height: 20vh;" class="alert alert-light alert-carrello-vuoto">
                    <div style="text-align: center; padding-bottom:20px;" class="testo-carrello-vuoto">
                        <strong>Non hai effettuato ordini.</strong>
                    </div>
                    <div style="text-align: center;" class="azione-carrello-vuoto">
                        <a href="{{ route('shop') }}" class="btn-vigneto-affitta">
                            Vai allo shop
                        </a>
                    </div>
            </div>
        @else
            @foreach($ordini as $o)
                <div class="catalogo-item">
                    <div class="catalogo-item-info">
                        <h3 class="catalogo-item-titolo">
                            Ordine #{{ $o->id }}
                        </h3>

                        <p class="catalogo-item-sottotitolo">
                            Data: {{ \Carbon\Carbon::parse($o->created_at)->format('d/m/Y H:i') }}
                        </p>

                        <p class="catalogo-item-sottotitolo">
                            Stato:
                            <span class="catalogo-item-stato">
                                {{ str_replace('_', ' ', $o->stato) }}
                            </span>
                        </p>

                        <p class="catalogo-item-prezzo">
                            Totale: {{ number_format($o->totale, 2, ',', '.') }} €
                        </p>

                        {{-- Dettaglio righe --}}
                        @php
                            $righe = $items[$o->id] ?? collect();
                        @endphp

                        @if($righe->count() > 0)
                            <div class="mt-2">
                                <p class="catalogo-item-sottotitolo mb-1"><strong>Prodotti:</strong></p>
                                <ul class="mb-0">
                                    @foreach($righe as $r)
                                        <li>
                                            {{ $r->prodotto_nome }} x{{ $r->quantita }}
                                            — {{ number_format($r->subtotale, 2, ',', '.') }} €
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class="catalogo-item-azioni">
                        {{-- Badge stato (solo estetica) --}}
                        @if($o->stato === 'confermato')
                            <span class="btn-catalogo-pill">Confermato</span>
                        @elseif($o->stato === 'spedito')
                            <span class="btn-catalogo-pill ">Spedito</span>
                        @elseif($o->stato === 'consegnato')
                            <span class="btn-catalogo-pill ">Consegnato</span>
                        @elseif($o->stato === 'annullato')
                            <span class="btn-catalogo-pill btn-catalogo-delete">Annullato</span>
                        @else
                            <span class="btn-catalogo-pill">
                                In attesa
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif

    </div>
</div>
@endsection
