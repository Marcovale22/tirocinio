@extends('base')

@section('title', config('app.name') . ' - Dettaglio evento')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dettaglio-evento.css') }}">
@endpush

@section('content')
<div class="pagina-evento-dettaglio">
    <div class="container sezione-dettaglio-evento">

        {{-- TITOLO IN ALTO --}}
        <h1 class="evento-title">{{ $prodotto->nome }}</h1>
        <p class="evento-subtitle">Dettagli dell’evento</p>

        <div class="row g-4 align-items-stretch">
            @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            {{-- IMMAGINE --}}
            <div class="col-lg-6 evento-left">
                <div class="evento-dettaglio-img-wrap">
                    <img
                        src="{{ asset('img/eventi/' . $prodotto->immagine) }}"
                        alt="{{ $prodotto->nome }}"
                        class="evento-dettaglio-img"
                    >
                </div>
            </div>


            {{-- INFO --}}
            <div class="col-lg-6">
                <div class="evento-dettaglio-card">
                    @if(!empty($evento->descrizione))
                    <p class="evento-dettaglio-descrizione">
                        <strong>Descrizione: </strong>{{ $evento->descrizione }}
                    </p>
                    @endif
                    <div class="evento-dettaglio-meta">
                        <div class="evento-dettaglio-riga">
                            <span class="label">Data</span>
                            <span class="value">
                                {{ \Carbon\Carbon::parse($evento->data_evento)->format('d/m/Y') }}
                            </span>
                        </div>

                        <div class="evento-dettaglio-riga">
                            <span class="label">Orario</span>
                            <span class="value">{{ \Carbon\Carbon::parse($evento->ora_evento)->format('H:i') }}</span>
                        </div>

                        <div class="evento-dettaglio-riga">
                            <span class="label">Luogo</span>
                            <span class="value">{{ $evento->luogo }}</span>
                        </div>

                        <div class="evento-dettaglio-riga">
                            <span class="label">Prezzo</span>
                            <span class="value">
                                {{ number_format($prodotto->prezzo, 2, ',', '.') }} €
                            </span>
                        </div>
                    </div>

                    <div class="evento-dettaglio-separatore"></div>
                    <h3>Vini presenti</h3>
                    @if($vini->isEmpty())
                        <div class="alert alert-warning mb-0">
                            Nessun vi sarannò prevesite degustazioni.
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($vini as $vino)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                    <a href="{{ route('vini.dettaglio', $vino->prodotto->id) }}"
                                    class="fw-semibold evento-vino-link">
                                        {{ $vino->prodotto->nome }}
                                    </a>
                                        @if(!empty($vino->annata))
                                            <small class="text-muted">Annata: {{ $vino->annata }}</small>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif


                    <div class="evento-dettaglio-separatore"></div>

                    {{-- AZIONE --}}
                    <div class="evento-dettaglio-actions d-grid gap-2">
                        @guest
                            <a href="{{ route('login') }}" class="btn evento-btn-primary">
                                Accedi per prenotare
                            </a>
                        @endguest

                        @can('isUtente')
                        <form action="{{ route('utente.eventi.prenotazioni.store', $evento->id) }}" method="POST" class="m-0">
                            @csrf

                            <div class="mb-2">
                                <label class="form-label fw-semibold mb-1">Numero posti</label>
                                <input type="number"
                                    name="posti"
                                    value="1"
                                    min="1"
                                    max="20"
                                    class="form-control">
                            </div>

                            @php
                                $nomeEvento = addslashes($prodotto->nome);
                                $dataEvento = \Carbon\Carbon::parse($evento->data_evento)->format('d/m/Y');
                            @endphp

                            <button type="submit"
                                    class="btn evento-btn-primary w-100"
                                    onclick="
                                        const posti = this.closest('form').querySelector('[name=posti]').value;
                                        return confirm(
                                            'Confermi la prenotazione?\n\n' +
                                            'Evento: {{ $nomeEvento }}\n' +
                                            'Data: {{ $dataEvento }}\n' +
                                            'Posti: ' + posti
                                        );
                                    ">
                                Prenota
                            </button>

                        </form>
                        @endcan

                        <div class="evento-dettaglio-back">
                            <a href="{{ url()->previous() }}">← Torna indietro</a>
                        </div>
                    </div>

                </div>

                

            </div>
        </div>
    </div>
</div>
@endsection
