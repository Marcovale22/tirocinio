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
                <a href="#" class="btn-catalogo-add"
                    data-bs-toggle="modal"
                    data-bs-target="#vinoModal"
                    data-mode="create">
                    + Aggiungi vino
                </a>
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
                         {{-- Modifica --}}
                        <button type="button"
                                class="btn-catalogo-pill mb-2"
                                data-bs-toggle="modal"
                                data-bs-target="#vinoModal"
                                data-mode="edit"
                                data-id="{{ $vino->id }}"
                                data-nome="{{ $vino->nome }}"
                                data-prezzo="{{ $vino->prezzo }}"
                                data-annata="{{ $vino->vinoDettaglio->annata ?? '' }}"
                                data-formato="{{ $vino->vinoDettaglio->formato ?? '' }}"
                                data-gradazione="{{ $vino->vinoDettaglio->gradazione ?? '' }}"
                                data-disponibilita="{{ $vino->disponibilita ?? 0 }}"
                                data-solfiti="{{ $vino->vinoDettaglio->solfiti ?? 0 }}">
                            Modifica
                        </button>

                        {{-- Elimina --}}
                        <form action="{{ route('catalogo.destroy.vino',$vino->id) }}"
                            method="POST"
                            onsubmit="return confirm('Eliminare questo vino?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn-catalogo-pill btn-catalogo-delete">
                                Elimina
                            </button>
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
                <a href="#" class="btn-catalogo-add"
                    data-bs-toggle="modal"
                    data-bs-target="#merchModal"
                    data-mode="create">+ Aggiungi merch</a>
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

                        {{-- Modifica MERCH --}}
                        <button type="button"
                                class="btn-catalogo-pill mb-2"
                                data-bs-toggle="modal"
                                data-bs-target="#merchModal"
                                data-mode="edit"
                                data-id="{{ $m->id }}"
                                data-nome="{{ $m->nome }}"
                                data-prezzo="{{ $m->prezzo }}"
                                data-disponibilita="{{ $m->disponibilita }}">
                            Modifica
                        </button>

                        {{-- Elimina MERCH --}}
                        <form action="{{ route('catalogo.destroy.merch',$m->id) }}"
                            method="POST"
                            onsubmit="return confirm('Eliminare questo prodotto?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-catalogo-pill btn-catalogo-delete">
                                Elimina
                            </button>
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
                <a href="#" class="btn-catalogo-add"
                    data-bs-toggle="modal"
                    data-bs-target="#eventoModal"
                    data-mode="create">
                    + Aggiungi evento</a>
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
                                Posti disponibili: {{ $e->disponibilita }}
                            </p>
                            <p class="catalogo-item-sottotitolo">
                                Luogo: {{ $e->evento->luogo }}
                            </p>
                            <p class="catalogo-item-sottotitolo">
                                Descrizione: {{ $e->evento->descrizione }}
                            </p>
                        @endif

                        <p class="catalogo-item-prezzo">
                            {{ number_format($e->prezzo, 2, ',', '.') }} €
                        </p>
                    </div>

                    <div class="catalogo-item-azioni">

                        {{-- Modifica EVENTO --}}
                        <button type="button"
                                class="btn-catalogo-pill mb-2"
                                data-bs-toggle="modal"
                                data-bs-target="#eventoModal"
                                data-mode="edit"
                                data-id="{{ $e->id }}"
                                data-nome="{{ $e->nome }}"
                                data-prezzo="{{ $e->prezzo }}"
                                data-data="{{ $e->evento->data_evento ?? '' }}"
                                data-ora="{{ $e->evento->ora_evento ?? '' }}"
                                data-luogo="{{ $e->evento->luogo ?? '' }}"
                                data-descrizione="{{ $e->evento->descrizione ?? '' }}"
                                data-disponibilita="{{ $e->disponibilita ?? '' }}">
                            Modifica
                        </button>

                        {{-- Elimina EVENTO --}}
                        <form action="{{ route('catalogo.destroy.evento',$e->id) }}"
                            method="POST"
                            onsubmit="return confirm('Eliminare questo evento?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-catalogo-pill btn-catalogo-delete">
                                Elimina
                            </button>
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
                <a href="#" class="btn-catalogo-add"
                    data-bs-toggle="modal"
                    data-bs-target="#vignetoModal"
                    data-mode="create">
                    + Aggiungi vigneto</a>
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
                            Lotti disponibili: {{ $v->disponibilita }}
                        </p>
                    </div>

                    <div class="catalogo-item-azioni">

                        {{-- Modifica VIGNETO --}}
                        <button type="button"
                                class="btn-catalogo-pill mb-2"
                                data-bs-toggle="modal"
                                data-bs-target="#vignetoModal"
                                data-mode="edit"
                                data-id="{{ $v->id }}"
                                data-nome="{{ $v->nome }}"
                                data-descrizione="{{ $v->descrizione }}"
                                data-prezzo="{{ $v->prezzo_annuo }}"
                                data-disponibilita="{{ $v->disponibilita }}">
                            Modifica
                        </button>

                        {{-- Elimina VIGNETO --}}
                        <form action="{{ route('catalogo.destroy.vigneto',$v->id) }}"
                            method="POST"
                            onsubmit="return confirm('Eliminare questo vigneto?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-catalogo-pill btn-catalogo-delete">
                                Elimina
                            </button>
                        </form>

                    </div>

                </div>
            @empty
                <p class="text-muted">Nessun vigneto presente.</p>
            @endforelse
        </section>

    </div>
</div>
@include('admin.modals.vino')
@include('admin.modals.merch')
@include('admin.modals.evento')
@include('admin.modals.vigneto')

@push('scripts')
    {{-- SCRIPT VINO --}}
    @include('admin.scripts.vino')
    @if ($errors->has('nome') || $errors->has('prezzo') || $errors->has('annata') ||
         $errors->has('formato') || $errors->has('gradazione') || $errors->has('immagine'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = new bootstrap.Modal(document.getElementById('vinoModal'));
                modal.show();
            });
        </script>
    @endif


    {{-- SCRIPT MERCH --}}
    @include('admin.scripts.merch')
    @if ($errors->has('nome') || $errors->has('prezzo') || $errors->has('disponibilita') || $errors->has('immagine'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = new bootstrap.Modal(document.getElementById('merchModal'));
                modal.show();
            });
        </script>
    @endif


    {{-- SCRIPT EVENTO --}}
    @include('admin.scripts.evento')
    @if ($errors->has('nome') || $errors->has('prezzo') || $errors->has('data_evento') ||
         $errors->has('ora_evento') || $errors->has('disponibilita') ||
         $errors->has('descrizione') || $errors->has('immagine'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = new bootstrap.Modal(document.getElementById('eventoModal'));
                modal.show();
            });
        </script>
    @endif


    {{-- SCRIPT VIGNETO --}}
    @include('admin.scripts.vigneto')
    @if ($errors->has('nome') || $errors->has('descrizione') || $errors->has('disponibilita') ||
         $errors->has('prezzo_annuo') || $errors->has('immagine'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = new bootstrap.Modal(document.getElementById('vignetoModal'));
                modal.show();
            });
        </script>
    @endif

@endpush



@endsection
