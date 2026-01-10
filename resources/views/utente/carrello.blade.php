@extends('base')

@section('title', config('app.name') . ' - Carrello')

@section('content')
<div class="pagina-catalogo py-4">
    <div class="container">

        <div class="text-center mb-4">
            <h1 class="titolo-catalogo mb-0">Carrello</h1>
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

        @if(count($cart) === 0)
            <div class="alert alert-light">
                Il carrello è vuoto.
            </div>
        @else

            @foreach($cart as $pid => $item)
                <div class="catalogo-item">

                    <div class="catalogo-item-img">
                        @php
                            $img = $item['immagine'] ?? 'placeholder.png';
                            $folder = $item['folder'] ?? 'prodotti';
                        @endphp

                        <img src="{{ asset("img/{$folder}/{$img}") }}"
                            alt="{{ $item['nome'] }}">
                    </div>

                    <div class="catalogo-item-info">
                        <h3 class="catalogo-item-titolo">{{ $item['nome'] }}</h3>

                        <p class="catalogo-item-prezzo">
                            {{ number_format($item['prezzo'], 2, ',', '.') }} €
                        </p>

                        <form method="POST" action="{{ route('carrello.update', $pid) }}" class="d-flex gap-2 align-items-center">
                            @csrf
                            <input type="number" name="quantita" min="1" value="{{ $item['quantita'] }}" class="form-control" style="max-width: 100px;">
                            <button class="btn-catalogo-pill" type="submit">Aggiorna</button>
                        </form>
                    </div>

                    <div class="catalogo-item-azioni">
                        <form method="POST" action="{{ route('carrello.remove', $pid) }}">
                            @csrf
                            <button type="submit" class="btn-catalogo-pill btn-catalogo-delete">
                                Rimuovi
                            </button>
                        </form>
                    </div>

                </div>
            @endforeach

            <div class="d-flex justify-content-between align-items-center mt-4">
                <h4 class="text-white mb-0">
                    Totale: € {{ number_format($totale, 2, ',', '.') }}
                </h4>

                <form method="POST" action="{{ route('utente.carrello.checkout') }}">
                    @csrf
                    <button class="btn-catalogo-add" type="submit">
                        Conferma ordine
                    </button>
                </form>
            </div>

        @endif
    </div>
</div>
@endsection
