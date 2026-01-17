@extends('base')

@section('title', config('app.name') . ' - Affitta Vigneto')

@section('content')
<div class="pagina-vigneti">
    <div class="container">

        <div class="pt-2">
            <div class="titolo-vigneti">Affitta un vigneto</div>
            <div class="sottotitolo-vigneti">
                Scopri i vigneti disponibili e invia la tua richiesta di affitto.
            </div>
        </div>

        {{-- Messaggi --}}
        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
        @endif

        <div class="sezione-vigneti">

        @if($vigneti->count() === 0)
            <div class="alert alert-light mb-0">
                Nessun vigneto disponibile al momento.
            </div>
        @else
            <div class="row g-4">
                @foreach($vigneti as $v)
                    @if ($v->disponibilita)
                    @php
                        $img = $v->immagine
                            ? asset('img/vigneti/' . $v->immagine)
                            : asset('img/vigneti/placeholder_vigneto.png');

                        $disp = $v->disponibilita ?? 0;
                        $isDisponibile = $disp > 0;
                    @endphp

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="vigneto-card h-100">

                            {{-- Immagine --}}
                            <div class="vigneto-img-wrap">
                                <img src="{{ $img }}" class="vigneto-img" alt="Immagine vigneto">
                            </div>

                            {{-- Corpo --}}
                            <div class="vigneto-body">
                                <div class="vigneto-nome">{{ $v->nome }}</div>

                                <div class="vigneto-descrizione">
                                    {{ $v->descrizione ?? 'Nessuna descrizione disponibile.' }}
                                </div>
                                
                                <div class="vigneto-disponibilita">
                                        @if ($disp == 1)
                                        Disponibilità: {{ $disp }} lotto
                                        @else
                                        Disponibilità: {{ $disp }} lotti
                                        @endif
                                </div>

                                <div class="vigneto-meta">
                                    <div class="vigneto-prezzo">
                                        € {{ number_format($v->prezzo_annuo, 2, ',', '.') }} / anno
                                    </div>
                                </div>
                            </div>

                            {{-- Azioni --}}
                            <div class="vigneto-actions">
                                @guest
                                <a href="{{ route('login') }}" class="btn-vigneto-affitta">
                                    Affitta
                                </a>
                                @endguest
                                @auth
                                <a href="{{ route('utente.vigneti.dettaglio', $v) }}" class="btn-vigneto-affitta">
                                    Affitta
                                </a>
                                @endauth    
                            </div>

                        </div>
                    </div>
                    
                    @endif
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection