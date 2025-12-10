@extends('base')

@section('title', config('app.name') . ' - Catalogo')

@section('content')
<div class="pagina-catalogo py-4">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="titolo-catalogo mb-0">Catalogo Prodotti</h1>
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

        {{-- NAVIGAZIONE RAPIDA --}}
        <div class="catalogo-nav mb-4">
            <a href="#vini" class="btn-catalogo-nav">Vini</a>
            <a href="#merch" class="btn-catalogo-nav">Merch</a>
            <a href="#eventi" class="btn-catalogo-nav">Eventi</a>
            <a href="#vigneti" class="btn-catalogo-nav">Vigneti</a>
        </div>

        {{-- SEZIONE VINI --}}
        <section id="vini" class="catalogo-sezione mb-5">
            <h2 class="catalogo-sezione-titolo mb-3">Vini</h2>

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
                            <p class="catalogo-item-sottotitolo">
                                Gradazione: {{ $vino->vino->gradazione }}°
                            </p>
                            <p class="catalogo-item-sottotitolo">
                                Solfiti: {{ $vino->vino->solfiti ?? 0 }} mg/l
                            </p>
                        @endif

                        <p class="catalogo-item-sottotitolo">
                            Disponibilità: {{ $vino->disponibilita ?? 0 }}
                        </p>

                        <p class="catalogo-item-prezzo">
                            {{ number_format($vino->prezzo, 2, ',', '.') }} €
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-muted">Nessun vino presente.</p>
            @endforelse
        </section>

        {{-- SEZIONE MERCH --}}
        <section id="merch" class="catalogo-sezione mb-5">
            <h2 class="catalogo-sezione-titolo mb-3">Merch</h2>

            @forelse ($merch as $m)
                <div class="catalogo-item">
                    <div class="catalogo-item-img">
                        <img src="{{ asset('img/merch/' . ($m->immagine ?? 'placeholder.png')) }}"
                             alt="{{ $m->nome }}">
                    </div>

                    <div class="catalogo-item-info">
                        <h3 class="catalogo-item-titolo">{{ $m->nome }}</h3>

                        <p class="catalogo-item-sottotitolo">
                            Disponibilità: {{ $m->disponibilita ?? 0 }}
                        </p>

                        <p class="catalogo-item-prezzo">
                            {{ number_format($m->prezzo, 2, ',', '.') }} €
                        </p>
                    </div>
                    <div class="catalogo-item-azioni">
                        <div class="mt-2">
                            <button type="button"
                                class="btn-catalogo-pill" 
                                data-bs-toggle="modal"
                                data-bs-target="#rifornimentoMerchModal"
                                data-id="{{ $m->id }}"
                                data-nome="{{ $m->nome }}">
                                + Rifornisci
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">Nessun prodotto merch presente.</p>
            @endforelse
        </section>

        {{-- SEZIONE EVENTI --}}
        <section id="eventi" class="catalogo-sezione mb-5">
            <h2 class="catalogo-sezione-titolo mb-3">Eventi</h2>

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
                                Luogo: {{ $e->evento->luogo }}
                            </p>
                            @if($e->evento->descrizione)
                                <p class="catalogo-item-sottotitolo">
                                    {{ $e->evento->descrizione }}
                                </p>
                            @endif
                        @endif

                        <p class="catalogo-item-sottotitolo">
                            Posti disponibili: {{ $e->disponibilita ?? 0 }}
                        </p>

                        <p class="catalogo-item-prezzo">
                            {{ number_format($e->prezzo, 2, ',', '.') }} €
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-muted">Nessun evento presente.</p>
            @endforelse
        </section>

        {{-- SEZIONE VIGNETI --}}
        <section id="vigneti" class="catalogo-sezione mb-5">
            <h2 class="catalogo-sezione-titolo mb-3">Vigneti</h2>

            @forelse ($vigneti as $v)
                <div class="catalogo-item">
                    <div class="catalogo-item-img">
                        <img src="{{ asset('img/vigneti/' . ($v->immagine ?? 'placeholder.png')) }}"
                             alt="{{ $v->nome }}">
                    </div>

                    <div class="catalogo-item-info">
                        <h3 class="catalogo-item-titolo">{{ $v->nome }}</h3>

                        @if($v->descrizione)
                            <p class="catalogo-item-sottotitolo">{{ $v->descrizione }}</p>
                        @endif

                        <p class="catalogo-item-sottotitolo">
                            Lotti disponibili: {{ $v->disponibilita ?? 0 }}
                        </p>

                        @if ($v->prezzo_annuo)
                            <p class="catalogo-item-prezzo">
                                {{ number_format($v->prezzo_annuo, 2, ',', '.') }} € / anno
                            </p>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-muted">Nessun vigneto presente.</p>
            @endforelse
        </section>

    </div>
</div>

<div class="modal fade" id="rifornimentoMerchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('staff.rifornimenti.store') }}">
                @csrf

                <input type="hidden" name="prodotto_id" id="rif-prodotto-id">

                <div class="modal-header">
                    <h5 class="modal-title">Richiesta di rifornimento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                </div>

                <div class="modal-body">
                    <p class="fw-bold mb-2" id="rif-prodotto-nome"></p>

                    <div class="mb-3">
                        <label class="form-label">Quantità da aggiungere</label>
                        <input type="number"
                               name="quantita"
                               class="form-control @error('quantita') is-invalid @enderror"
                               min="1"
                               required>
                        @error('quantita')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- eventuale messaggio di info --}}
                    <small class="text-muted">
                        La richiesta verrà inviata all'amministratore, che potrà confermarla o annullarla.
                    </small>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-success">Invia richiesta</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rifModal = document.getElementById('rifornimentoMerchModal');
    if (!rifModal) return;

    rifModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button) return;

        const id   = button.getAttribute('data-id');
        const nome = button.getAttribute('data-nome');

        const idInput   = document.getElementById('rif-prodotto-id');
        const nomeLabel = document.getElementById('rif-prodotto-nome');

        if (idInput)   idInput.value = id;
        if (nomeLabel) nomeLabel.textContent = 'Prodotto: ' + nome;
    });
});
</script>
@endsection
