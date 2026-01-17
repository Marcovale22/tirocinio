@extends('base')

@section('title', config('app.name') . ' - ' . $vigneto->nome)

@section('content')
<div class="pagina-vigneto-dettaglio">
    <div class="container">

        <div class="pt-2">
            <div class="titolo-vigneti">{{ $vigneto->nome }}</div>
            <div class="sottotitolo-vigneti">
                Dettagli del vigneto e richiesta di affitto.
            </div>
        </div>

        {{-- Messaggi --}}
        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
        @endif

        <div class="sezione-dettaglio-vigneto">
            <div class="row g-4">

                {{-- Immagine --}}
                <div class="col-12 col-lg-6">
                    @php
                        $img = $vigneto->immagine
                            ? asset('img/vigneti/' . $vigneto->immagine)
                            : asset('img/vigneti/placeholder_vigneto.png');
                    @endphp

                    <div class="vigneto-dettaglio-img-wrap">
                        <img src="{{ $img }}" class="vigneto-dettaglio-img" alt="Immagine vigneto">
                    </div>
                </div>

                {{-- Info --}}
                <div class="col-12 col-lg-6">
                    <div class="vigneto-dettaglio-card">

                        <div class="vigneto-dettaglio-descrizione">
                            {{ $vigneto->descrizione ?? 'Nessuna descrizione disponibile.' }}
                        </div>

                        <div class="vigneto-dettaglio-meta">
                            <div class="vigneto-dettaglio-riga">
                                <span class="label">Prezzo annuo</span>
                                <span class="value">€ {{ number_format($vigneto->prezzo_annuo, 2, ',', '.') }}</span>
                            </div>

                            <div class="vigneto-dettaglio-riga">
                                <span class="label">Disponibilità</span>
                                <span class="value">
                                    {{ $vigneto->disponibilita }}
                                    {{ $vigneto->disponibilita == 1 ? 'lotto' : 'lotti' }}
                                </span>
                            </div>
                        </div>

                        <div class="vigneto-dettaglio-separatore"></div>

                        {{-- Placeholder futuri --}}
                        <div class="vigneto-dettaglio-extra">
                            <div class="extra-item">
                            <span class="label">Produzione stimata</span>
                            <span class="value">
                                {{ $vigneto->bottiglie_stimate ? $vigneto->bottiglie_stimate . ' bottiglie' : '—' }}
                            </span>
                            </div>

                            <div class="extra-item">
                            <span class="label">Tipo di vino</span>
                            <span class="value">{{ $vigneto->tipo_vino ?? '—' }}</span>
                            </div>

                            <div class="extra-item">
                            <span class="label">Fase attuale</span>
                            <span class="value">{{ $vigneto->fase_produzione ?? '—' }}</span>
                            </div>
                        </div>

                        {{-- Azione --}}
                        <div class="vigneto-dettaglio-actions">
                            @auth
                                @if($vigneto->disponibilita > 0)
                                    <form method="POST" action="{{ route('utente.vigneti.richiesta.store', $vigneto) }}">
                                        @csrf
                                        <input type="hidden" name="annata" value="{{ now()->year }}">
                                        <button class="btn-vigneto-affitta w-100">Invia richiesta</button>
                                    </form>
                                @else
                                    <div class="alert alert-warning mb-0">
                                        Questo vigneto non è disponibile al momento.
                                    </div>
                                @endif
                            @endauth

                            @guest
                                <a href="{{ route('login') }}" class="btn-vigneto-affitta w-100">
                                    Accedi per affittare
                                </a>
                            @endguest
                        </div>

                        <div class="vigneto-dettaglio-back">
                            <a href="{{ route('affitta') }}">← Torna ai vigneti</a>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
