@extends('base')

@section('title', config('app.name') . ' - Catalogo')

@section('content')
<div class="pagina-catalogo py-4">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="titolo-catalogo mb-0">Catalogo Prodotti</h1>
        </div>

        {{-- NAVIGAZIONE RAPIDA --}}
        <div class="catalogo-nav mb-4">
            <a href="#vini" class="btn-catalogo-nav">Vini</a>
            <a href="#merch" class="btn-catalogo-nav">Merch</a>
            <a href="#eventi" class="btn-catalogo-nav">Eventi</a>
            <a href="#vigneti" class="btn-catalogo-nav">Vigneti</a>
        </div>


        {{-- SEZIONE VINI --}}
        <section id="vini" class="catalogo-sezione mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="catalogo-sezione-titolo">Vini</h2>
                <a href="#" class="btn-catalogo-add">+ Aggiungi vino</a>
            </div>

            @forelse ($vini as $vino)
                <div class="catalogo-item">
                    <div class="catalogo-item-img">
                        <img src="{{ asset('img/vini/' . ($vino->immagine ?? 'placeholder.png')) }}"
                             alt="{{ $vino->nome }}">
                    </div>

                    <div class="catalogo-item-info">
                        <h3 class="catalogo-item-titolo">{{ $vino->nome }}</h3>

                        @if ($vino->vino)
                            <p class="catalogo-item-sottotitolo">
                                Annata {{ $vino->vino->annata }} — Formato {{ $vino->vino->formato }}
                            </p>
                        @endif

                        <p class="catalogo-item-prezzo">
                            {{ number_format($vino->prezzo, 2, ',', '.') }} €
                        </p>
                    </div>

                    <div class="catalogo-item-azioni">
                        <a href="#" class="btn btn-sm btn-warning mb-2">Modifica</a>
                        <form action="#" method="POST" onsubmit="return confirm('Eliminare questo vino?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Elimina</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-muted">Nessun vino presente.</p>
            @endforelse
        </section>



        {{-- SEZIONE MERCH --}}
        <section id="merch" class="catalogo-sezione mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="catalogo-sezione-titolo">Merch</h2>
                <a href="#" class="btn-catalogo-add">+ Aggiungi merch</a>
            </div>

            @forelse ($merch as $m)
                <div class="catalogo-item">
                    <div class="catalogo-item-img">
                        <img src="{{ asset('img/merch/' . ($m->immagine ?? 'placeholder.png')) }}"
                             alt="{{ $m->nome }}">
                    </div>

                    <div class="catalogo-item-info">
                        <h3 class="catalogo-item-titolo">{{ $m->nome }}</h3>
                        <p class="catalogo-item-prezzo">
                            {{ number_format($m->prezzo, 2, ',', '.') }} €
                        </p>
                    </div>

                    <div class="catalogo-item-azioni">
                        <a href="#" class="btn btn-sm btn-warning mb-2">Modifica</a>
                        <form action="#" method="POST" onsubmit="return confirm('Eliminare questo prodotto?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Elimina</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-muted">Nessun prodotto merch presente.</p>
            @endforelse
        </section>



        {{-- SEZIONE EVENTI --}}
        <section id="eventi" class="catalogo-sezione mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="catalogo-sezione-titolo">Eventi</h2>
                <a href="#" class="btn-catalogo-add">+ Aggiungi evento</a>
            </div>

            @forelse ($eventi as $e)
                <div class="catalogo-item">
                    <div class="catalogo-item-img">
                        <img src="{{ asset('img/eventi/' . ($e->immagine ?? 'placeholder.png')) }}"
                             alt="{{ $e->nome }}">
                    </div>

                    <div class="catalogo-item-info">
                        <h3 class="catalogo-item-titolo">{{ $e->nome }}</h3>

                        @if ($e->evento)
                            <p class="catalogo-item-sottotitolo">
                                {{ \Carbon\Carbon::parse($e->evento->data_evento)->format('d-m-Y') }}

                                @if ($e->evento->ora_evento)
                                    — {{ \Carbon\Carbon::parse($e->evento->ora_evento)->format('H:i') }}
                                @endif
                            </p>
                            <p class="catalogo-item-sottotitolo">
                                Posti disponibili: {{ $e->evento->disponibilita }}
                            </p>
                        @endif

                        <p class="catalogo-item-prezzo">
                            {{ number_format($e->prezzo, 2, ',', '.') }} €
                        </p>
                    </div>

                    <div class="catalogo-item-azioni">
                        <a href="#" class="btn btn-sm btn-warning mb-2">Modifica</a>
                        <form action="#" method="POST" onsubmit="return confirm('Eliminare questo evento?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Elimina</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-muted">Nessun evento presente.</p>
            @endforelse
        </section>



        {{-- SEZIONE VIGNETI --}}
        <section id="vigneti" class="catalogo-sezione mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="catalogo-sezione-titolo">Vigneti</h2>
                <a href="#" class="btn-catalogo-add">+ Aggiungi vigneto</a>
            </div>

            @forelse ($vigneti as $v)
                <div class="catalogo-item">
                    <div class="catalogo-item-img">
                        <img src="{{ asset('img/vigneti/' . ($v->immagine ?? 'placeholder.png')) }}"
                             alt="{{ $v->nome }}">
                    </div>

                    <div class="catalogo-item-info">
                        <h3 class="catalogo-item-titolo">{{ $v->nome }}</h3>

                        <p class="catalogo-item-sottotitolo">{{ $v->descrizione }}</p>

                        @if ($v->prezzo_annuo)
                            <p class="catalogo-item-prezzo">
                                {{ number_format($v->prezzo_annuo, 2, ',', '.') }} € / anno
                            </p>
                        @endif

                        <p class="catalogo-item-sottotitolo">
                            Lotti disponibili: {{ $v->disponibilita_totale }}
                        </p>
                    </div>

                    <div class="catalogo-item-azioni">
                        <a href="#" class="btn btn-sm btn-warning mb-2">Modifica</a>
                        <form action="#" method="POST" onsubmit="return confirm('Eliminare questo vigneto?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Elimina</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-muted">Nessun vigneto presente.</p>
            @endforelse
        </section>

    </div>
</div>
@endsection
