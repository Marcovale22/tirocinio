@extends('base')

@section('title', config('app.name') . ' - Ordini Staff')

@section('content')
<div class="pagina-catalogo py-4">
    <div class="container">

        <div class="text-center mb-4">
            <h1 class="titolo-catalogo mb-0">Ordini</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($ordini->count() === 0)
            <div class="alert alert-light">
                Nessun ordine presente.
            </div>
        @else
            @foreach($ordini as $o)
                @php
                    $righe = $items[$o->id] ?? collect();
                @endphp

                <div class="catalogo-item">
                    <div class="catalogo-item-info">
                        <h3 class="catalogo-item-titolo">Ordine #{{ $o->id }}</h3>

                        <p class="catalogo-item-sottotitolo">
                            Utente: {{ $o->username }} ({{ $o->email }})
                        </p>

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

                        @if($righe->count() > 0)
                            <div class="mt-2">
                                <p class="catalogo-item-sottotitolo mb-1"><strong>Righe:</strong></p>
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

                        {{-- Conferma --}}
                        @if($o->stato !== 'confermato' && $o->stato !== 'annullato')
                            <form method="POST" action="{{ route('staff.ordini.stato', $o->id) }}">
                                @csrf
                                <input type="hidden" name="stato" value="confermato">
                                <button type="submit" class="btn-catalogo-pill"
                                onclick="return confirm('Vuoi Conferma l\'ordine?')">
                                    Conferma
                                </button>
                            </form>
                        @endif

                        {{-- Spedisci --}}
                        @if($o->stato === 'confermato')
                            <form method="POST" action="{{ route('staff.ordini.stato', $o->id) }}">
                                @csrf
                                <input type="hidden" name="stato" value="spedito">
                                <button type="submit" class="btn-catalogo-pill"
                                onclick="return confirm('Vuoi impostare come stato spedito?')">
                                    Spedisci
                                </button>
                            </form>
                        @endif

                        {{-- Consegna --}}
                        @if($o->stato === 'spedito')
                            <form method="POST" action="{{ route('staff.ordini.stato', $o->id) }}">
                                @csrf
                                <input type="hidden" name="stato" value="consegnato">
                                <button type="submit" class="btn-catalogo-pill"
                                onclick="return confirm('Vuoi impostare come stato consegnato?')">
                                    Consegnato
                                </button>
                            </form>
                        @endif  


                        {{-- Annulla --}}
                        @if($o->stato !== 'annullato')
                            <form method="POST" action="{{ route('staff.ordini.stato', $o->id) }}">
                                @csrf
                                <input type="hidden" name="stato" value="annullato">
                                <button type="submit" class="btn-catalogo-pill btn-catalogo-delete"
                                onclick="return confirm('Vuoi Annullare l\'ordine?')">
                                    Annulla
                                </button>
                            </form>
                        @endif

                    </div>
                </div>
            @endforeach
        @endif

    </div>
</div>
@endsection
