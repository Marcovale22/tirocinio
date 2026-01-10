@extends('base')

@section('title', config('app.name') . ' - Shop')

@section('content')
<div class="pagina-catalogo py-4">
    <div class="container">

        <div class="text-center mb-4">
            <h1 class="titolo-catalogo mb-0">Shop</h1>
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
        </div>

        {{-- SEZIONE VINI --}}
        <section id="vini" class="catalogo-sezione mb-5">
            <h2 class="catalogo-sezione-titolo mb-3">Vini</h2>

            @foreach ($vini as $vino)
                @if ($vino->disponibilita > 1)
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

                            <p class="catalogo-item-prezzo">
                                {{ number_format($vino->prezzo, 2, ',', '.') }} €
                            </p>
                        </div>

                        <div class="vigneto-actions">
                        @guest
                            <a href="{{ route('login') }}" class="btn-vigneto-affitta">
                                Acquista
                            </a>
                        @endguest
                        @auth
                            <a href="" class="btn-vigneto-affitta">
                                Acquista
                            </a>
                        @endauth    
                    </div>
                    </div>
                @endif
            @endforeach
        </section>

        {{-- SEZIONE MERCH --}}
        <section id="merch" class="catalogo-sezione mb-5">
            <h2 class="catalogo-sezione-titolo mb-3">Merch</h2>

            @foreach ($merch as $m)
                @if ($m->disponibilita > 1)
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

                    <div class="vigneto-actions">
                        @guest
                            <a href="{{ route('login') }}" class="btn-vigneto-affitta">
                                Acquista
                            </a>
                        @endguest
                        @auth
                            <a href="" class="btn-vigneto-affitta">
                                Acquista
                            </a>
                        @endauth    
                    </div>
                </div>
                @endif
            @endforeach
        </section>

        {{-- SEZIONE EVENTI --}}
        <section id="eventi" class="catalogo-sezione mb-5">
            <h2 class="catalogo-sezione-titolo mb-3">Eventi</h2>

            @foreach ($eventi as $e)
                @if ($e->disponibilita > 1)
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

                            <p class="catalogo-item-prezzo">
                                {{ number_format($e->prezzo, 2, ',', '.') }} €
                            </p>
                        </div>

                        <div class="vigneto-actions">
                                @guest
                                    <a href="{{ route('login') }}" class="btn-vigneto-affitta">
                                        Acquista
                                    </a>
                                @endguest
                                @auth
                                    <a href="" class="btn-vigneto-affitta">
                                        Acquista
                                    </a>
                                @endauth    
                        </div>
                    </div> 
                @endif
            @endforeach
        </section>

       

    </div>
</div>

@endsection