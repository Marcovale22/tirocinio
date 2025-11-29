@extends('base')

@section('title', config('app.name') . ' - Vini')

@section('content')
<div class="pagina-vini">
    <div class="container text-center mb-1 ">
        <h1 class="titolo-vini mb-2">Tutti i vini</h1>
        <p class="sottotitolo-vini mb-5">Tutti i vini di Bellò</p>

        <div class="row justify-content-center align-items-center sezione-vini">
            @foreach ($vini as $prodotto)
                <div class="col-12 col-md-4 d-flex justify-content-center mb-4 mb-md-0">
                    <div class="vino-card align-items-center">
                        {{-- BOTTIGLIA A SINISTRA --}}
                        <img src="{{ asset('img/vini/' . $prodotto->immagine) }}"
                             alt="{{ $prodotto->nome }}"
                             class="vino-img">

                        {{-- TESTO A DESTRA --}}
                        <div class="vino-testo ">
                            <h3 class="vino-nome">{{ $prodotto->nome }}</h3>

                            <button class="btn-vino-acquista mt-3">Acquista</button>

                            
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>
@endsection

