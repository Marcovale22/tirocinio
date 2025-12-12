@extends('base')

@section('title', config('app.name') . ' - Storico rifornimenti')

@section('content')
<div class="pagina-catalogo py-4">
    <div class="container">

        {{-- TITOLO + TORNA ALLE RICHIESTE --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="titolo-catalogo mb-0">Storico rifornimenti</h1>

            <a href="{{ route('admin.rifornimenti.getRifornimenti') }}" class="btn-catalogo-add">
                Richieste in lavorazione
            </a>
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

        @if ($rifornimenti->isEmpty())
            <p class="text-white mt-4">
                Nessun rifornimento confermato o annullato.
            </p>
        @else
            @foreach ($rifornimenti as $rifornimento)

                <div class="catalogo-item">

                    {{-- IMMAGINE PRODOTTO --}}
                    <div class="catalogo-item-img">
                        @php
                            $immagine = $rifornimento->prodotto->immagine ?? 'img/placeholder.png';
                        @endphp
                        <img src="{{ asset($immagine) }}"
                             alt="{{ $rifornimento->prodotto->nome ?? 'Prodotto' }}">
                    </div>

                    {{-- INFO --}}
                    <div class="catalogo-item-info">
                        <h3 class="catalogo-item-titolo">
                            {{ $rifornimento->prodotto->nome ?? 'Prodotto #'.$rifornimento->prodotto_id }}
                        </h3>

                        <p class="catalogo-item-sottotitolo">
                            Richiesto da: <strong>{{ $rifornimento->user->name ?? 'N/D' }}</strong>
                        </p>

                        <p class="catalogo-item-sottotitolo">
                            Quantità richiesta: <strong>{{ $rifornimento->quantita }}</strong>
                        </p>

                        @if ($rifornimento->note)
                            <p class="catalogo-item-sottotitolo">
                                Note: {{ $rifornimento->note }}
                            </p>
                        @endif

                        {{-- STATO --}}
                        <p class="catalogo-item-sottotitolo catalogo-item-stato">
                            Stato:
                            <strong>
                                {{ ucfirst(str_replace('_', ' ', $rifornimento->stato)) }}
                            </strong>
                        </p>
                    </div>

                    {{-- QUI NIENTE BOTTONI: SOLO STORICO --}}
                    <div class="catalogo-item-azioni">
                        {{-- Volendo puoi mostrare data conferma/annullamento --}}
                        <span class="text-muted" style="font-size: 0.85rem;">
                            Aggiornato il: {{ $rifornimento->updated_at?->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </div>

            @endforeach
        @endif

    </div>
</div>
@endsection
