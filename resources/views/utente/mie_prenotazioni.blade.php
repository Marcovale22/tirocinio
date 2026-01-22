@extends('base')

@section('title', 'Le mie prenotazioni')


@section('content')

<div class="Pagina-mie-prenotazioni">
    <div class="prenotazioni-header">
        <h1>Le mie prenotazioni</h1>
        <p>Qui trovi tutte le prenotazioni degli eventi effettuate con il tuo account.</p>
    </div>

    <div class="container pb-5">

        {{-- FLASH --}}
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

        @if($prenotazioni->isEmpty())
            <div class="alert alert-info">
                Non hai ancora effettuato prenotazioni.
            </div>
        @else
            <div class="row g-4">
                @foreach($prenotazioni as $p)
                    @php
                        $evento   = $p->evento;
                        $prodotto = $evento?->prodotto;
                        $vini     = $evento?->vini ?? collect();
                    @endphp

                    <div class="col-12">
                        <div class="card prenotazione-card">
                            <div class="card-body pren-card-body">

                                {{-- IMMAGINE (poster) --}}
                                <div class="pren-img-wrap">
                                    @if($prodotto && $prodotto->immagine)
                                        <img
                                            src="{{ asset('img/eventi/' . $prodotto->immagine) }}"
                                            alt="{{ $prodotto->nome }}"
                                            class="pren-img">
                                    @endif
                                </div>

                                {{-- INFO + VINI --}}
                                <div class="flex-grow-1">

                                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                        <h5 class="mb-0">{{ $prodotto?->nome }}</h5>

                                        @php
                                            $badge = match($p->stato) {
                                                'confermata' => 'badge-confermata',
                                                'annullata'  => 'badge-annullata',
                                                default      => 'badge-in-attesa',
                                            };
                                        @endphp

                                        <span class="badge-stato {{ $badge }}">
                                            {{ str_replace('_',' ',$p->stato) }}
                                        </span>
                                    </div>

                                    <div class="text-muted small">
                                        {{ \Carbon\Carbon::parse($evento->data_evento)->format('d/m/Y') }}
                                        alle {{ \Carbon\Carbon::parse($evento->ora_evento)->format('H:i') }}
                                        • {{ $evento->luogo }}
                                    </div>

                                    <div class="mt-2">
                                        <strong>Posti:</strong> {{ $p->posti }} •
                                        <strong>Prenotata il:</strong> {{ $p->created_at->format('d/m/Y') }}
                                    </div>

                                    {{-- VINI --}}
                                    <div class="vini-box mt-3">
                                        <h6>Vini presenti all’evento</h6>

                                        @if($vini->isEmpty())
                                            <div class="text-muted small">Nessun vino associato.</div>
                                        @else
                                            @foreach($vini as $vino)
                                                <div class="vino-item">
                                                    <div>
                                                        <a href="{{ route('vini.dettaglio', $vino->prodotto->id) }}"
                                                        class="vino-link">
                                                            {{ $vino->prodotto->nome }}
                                                        </a>
                                                        <div class="text-muted small">
                                                            Annata {{ $vino->annata }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                {{-- AZIONE: ANNULLA (in basso a destra nella card) --}}
                                @if(in_array($p->stato, ['in_attesa','confermata']))
                                    <form action="{{ route('utente.prenotazioni.annulla',$p->id) }}"
                                        method="POST"
                                        class="pren-annulla">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="btn btn-annulla"
                                                onclick="return confirm('Vuoi annullare la prenotazione?')">
                                            Annulla
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </div>
                    </div>

                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
