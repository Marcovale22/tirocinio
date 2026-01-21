@extends('base')

@section('title', config('app.name') . ' - Dettaglio vino')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dettaglio-vino.css') }}">
@endpush

@section('content')
<div class="pagina-vino-dettaglio">
    <div class="container sezione-dettaglio-vino">

        <div class="pt-2">
            <div class="titolo-vino">{{ $prodotto->nome }}</div>
            <div class="sottotitolo-vino">
                Dettagli del vino.
            </div>
        </div>

        {{-- Messaggi --}}
        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
        @endif

        <div class="row g-4 align-items-stretch">

            {{-- IMMAGINE --}}
            <div class="col-lg-6">
                <div class="vino-dettaglio-img-wrap">
                    <img
                        src="{{ asset('img/vini/' . $prodotto->immagine) }}"
                        alt="{{ $prodotto->nome }}"
                        class="vino-dettaglio-img"
                    >
                </div>
            </div>

            {{-- INFO --}}
            <div class="col-lg-6">
                <div class="vino-dettaglio-card">
                

                    <p class="vino-dettaglio-descrizione">
                        {{ $prodotto->descrizione }}
                    </p>

                    <div class="vino-dettaglio-meta">
                        <div class="vino-dettaglio-riga">
                            <span class="label">Annata</span>
                            <span class="value">{{ $vino->annata }}</span>
                        </div>

                        <div class="vino-dettaglio-riga">
                            <span class="label">Gradazione</span>
                            <span class="value">{{ $vino->gradazione }}°</span>
                        </div>

                        <div class="vino-dettaglio-riga">
                            <span class="label">Formato</span>
                            <span class="value">{{ $vino->formato }}</span>
                        </div>

                        <div class="vino-dettaglio-riga">
                            <span class="label">Prezzo</span>
                            <span class="value">{{ number_format($prodotto->prezzo, 2, ',', '.') }} €</span>
                        </div>
                    </div>

                    <div class="vino-dettaglio-separatore"></div>

                    {{-- AZIONE PRINCIPALE --}}
                    <div class="vino-dettaglio-actions d-grid gap-2">
                        @guest
                            <a href="{{ route('login') }}" class="btn vino-btn-primary">
                                Accedi per acquistare
                            </a>
                        @endguest

                        @can('isUtente')
                            <form action="{{ route('utente.carrello.add', $prodotto->id) }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="btn vino-btn-primary w-100">
                                    Aggiungi al carrello
                                </button>
                            </form>
                        @endcan

                        <div class="vino-dettaglio-back">
                            <a href="{{ url()->previous() }}">← Torna indietro</a>
                        </div>
                    </div>

                </div>



            </div>

        </div>
    </div>
</div>
@endsection
