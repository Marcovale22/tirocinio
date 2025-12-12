@extends('base')

@section('title', config('app.name') . ' - Rifornimenti')

@section('content')
<div class="pagina-catalogo py-4">
    <div class="container">

        

        {{-- ALERT SUCCESSO / ERRORE --}}
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

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="titolo-catalogo mb-0">Richieste di rifornimento</h1>

            <a href="{{ route('admin.rifornimenti.storico') }}" class="btn-catalogo-add">
                Storico ordini
            </a>
        </div>


        {{-- LISTA RIFORNIMENTI --}}
        @if ($rifornimenti->isEmpty())
            <p class="text-white mt-4">
                Non ci sono richieste di rifornimento in lavorazione.
            </p>
        @else
            @foreach ($rifornimenti as $rifornimento)

                <div class="catalogo-item">

                    {{-- IMMAGINE PRODOTTO (SE C'È) --}}
                    <div class="catalogo-item-img">
                        @php
                            // Se nel prodotto hai un campo 'immagine' con il path relativo
                            $immagine = $rifornimento->prodotto->immagine ?? 'img/merch/placeholder_merch.png';
                        @endphp
                        <img src="{{ asset($immagine) }}"
                             alt="{{ $rifornimento->prodotto->nome ?? 'Prodotto' }}">
                    </div>

                    {{-- INFO PRODOTTO + RICHIESTA --}}
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

                        <p class="catalogo-item-sottotitolo catalogo-item-stato">
                            Stato: {{ ucfirst(str_replace('_', ' ', $rifornimento->stato)) }}
                        </p>
                    </div>

                    {{-- AZIONI: CONFERMA / ANNULLA --}}
                    <div class="catalogo-item-azioni">

                        {{-- Bottone CONFERMA: apre il suo modal --}}
                        <button type="button"
                                class="btn-catalogo-pill"
                                data-bs-toggle="modal"
                                data-bs-target="#modalConferma{{ $rifornimento->id }}">
                            Conferma rifornimento
                        </button>

                        {{-- Bottone ANNULLA: apre il suo modal --}}
                        <button type="button"
                                class="btn-catalogo-pill btn-catalogo-delete"
                                data-bs-toggle="modal"
                                data-bs-target="#modalAnnulla{{ $rifornimento->id }}">
                            Annulla rifornimento
                        </button>

                    </div>
                </div>

                {{-- MODAL CONFERMA --}}
                <div class="modal fade" id="modalConferma{{ $rifornimento->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">Conferma rifornimento</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                            </div>

                            <div class="modal-body">
                                Confermi il rifornimento del prodotto
                                <strong>{{ $rifornimento->prodotto->nome ?? 'Prodotto' }}</strong>
                                per la quantità di <strong>{{ $rifornimento->quantita }}</strong>?
                            </div>

                            <div class="modal-footer">
                                <form action="{{ route('admin.rifornimenti.aggiornaStato', $rifornimento->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="azione" value="conferma">

                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Annulla
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        Conferma rifornimento
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- MODAL ANNULLA --}}
                <div class="modal fade" id="modalAnnulla{{ $rifornimento->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">Annulla rifornimento</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                            </div>

                            <div class="modal-body">
                                Sei sicuro di voler annullare il rifornimento del prodotto
                                <strong>{{ $rifornimento->prodotto->nome ?? 'Prodotto' }}</strong>?
                            </div>

                            <div class="modal-footer">
                                <form action="{{ route('admin.rifornimenti.aggiornaStato', $rifornimento->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="azione" value="annulla">

                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Chiudi
                                    </button>
                                    <button type="submit" class="btn btn-danger">
                                        Annulla rifornimento
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            @endforeach
        @endif

    </div>
</div>

{{-- -{{ route('admin.rifornimenti.stato', $rifornimento->id) }} --}}
@endsection
 