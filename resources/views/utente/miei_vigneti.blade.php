@extends('base')

@section('title', config('app.name') . ' - I miei vigneti')

@section('content')
<div class="pagina-miei-vigneti">
    <div class="container">

        <div class="pt-2">
            <div class="titolo-vigneti">I miei vigneti</div>
            <div class="sottotitolo-vigneti">
                Qui trovi le tue richieste e lo stato di lavorazione del vigneto.
            </div>
        </div>

        {{-- Messaggi --}}
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

        <div class="sezione-miei-vigneti mt-3">
            @if($richieste->count() === 0)
                <div style="height: 20vh;" class="alert alert-light alert-carrello-vuoto">
                    <div style="text-align: center; padding-bottom: 20px;" class="testo-carrello-vuoto">
                        <strong>Non hai ancora affittato nessun vigneto.</strong>
                    </div>
                    <div style="text-align: center;" class="azione-carrello-vuoto">
                        <a href="{{ route('affitta') }}" class="btn-vigneto-affitta">
                            Vai ai vigneti
                        </a>
                    </div>
                </div>
            @else
                <div class="row g-4">
                    @foreach($richieste as $r)
                        @php
                            $v = $r->vigneto;

                            $img = $v->immagine
                                ? asset('img/vigneti/' . $v->immagine)
                                : asset('img/vigneti/placeholder_vigneto.png');

                            // label stati richiesta
                            $statoLabel = match($r->stato) {
                                'in_attesa' => 'In attesa',
                                'confermato' => 'Confermato',
                                'rifiutato' => 'Rifiutato',
                                'annullato' => 'Annullato',
                                default => $r->stato
                            };

                            // fase produzione (catalogo)
                            $fase = $v->fase_produzione ?? null;

                            $fasi = [
                                'potatura' => 10,
                                'germogliamento' => 20,
                                'fioritura' => 35,
                                'invaiatura' => 50,
                                'vendemmia' => 65,
                                'vinificazione' => 80,
                                'affinamento' => 90,
                                'imbottigliamento' => 95,
                                'pronto' => 100,
                            ];

                            $progress = $fase && isset($fasi[$fase]) ? $fasi[$fase] : 0;

                            $faseLabel = $fase ? ucfirst(str_replace('_', ' ', $fase)) : '—';
                            $tipoVino = $v->tipo_vino ? ucfirst($v->tipo_vino) : '—';
                            $bottiglie = $v->bottiglie_stimate ? $v->bottiglie_stimate . ' bottiglie' : '—';
                        @endphp

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="mio-vigneto-card h-100">

                                <div class="mio-vigneto-img-wrap">
                                    <img src="{{ $img }}" class="mio-vigneto-img" alt="Immagine vigneto">
                                </div>

                                <div class="mio-vigneto-body">
                                    <div class="mio-vigneto-top">
                                        <div class="mio-vigneto-nome">{{ $v->nome }}</div>
                                        <span class="badge-stato stato-{{ $r->stato }}">{{ $statoLabel }}</span>
                                    </div>

                                    <div class="mio-vigneto-annata">
                                        Annata: <strong>{{ $r->annata }}</strong>
                                    </div>

                                    <div class="mio-vigneto-info">
                                        <div><strong>Tipo vino:</strong> {{ $tipoVino }}</div>
                                        <div><strong>Produzione stimata:</strong> {{ $bottiglie }}</div>
                                    </div>

                                    <div class="mio-vigneto-fase">
                                        <div class="fase-header">
                                            <span><strong>Fase produzione:</strong> {{ $faseLabel }}</span>
                                            <span class="fase-percent">{{ $progress }}%</span>
                                        </div>

                                        <div class="fase-bar">
                                            <div class="fase-bar-fill"style="width: {{ isset($progress) ? $progress : 0 }}%;"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mio-vigneto-actions">
                                    

                                    @if($r->stato === 'in_attesa')
                                        <form method="POST" action="{{ route('utente.vigneti.richieste.annulla', $r) }}">
                                            @csrf
                                            <button class="btn btn-outline-danger btn-sm"
                                                onclick="return confirm('Sei sicuro di voler annullare la richiesta?')">
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
</div>
@endsection
