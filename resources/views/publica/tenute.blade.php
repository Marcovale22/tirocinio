@extends('base')

@section('title', config('app.name') . ' - Tenute')

@section('content')

<div class="tenute-wrapper">

    <!-- SEZIONE 1 - SFONDO ARANCIO -->
    <div class="tenuta-section bg-orange first-section">

         {{-- Titolo in alto dentro lo stesso blocco arancione --}}
        <h1 class="titolo-tenute">Le Tenute</h1>

        {{-- Contenuto: immagine + testo --}}
        <div class="tenuta-container">
            <div class="tenuta-img-col">
                <img src="{{ asset('img/tenute/prima_tenuta.jpg') }}" alt="Tenuta San Lorenzo">
            </div>

            <div class="tenuta-info-col">
                <h2>Tenuta San Lorenzo</h2>
                <p>
                    Una storica tenuta immersa nei colli marchigiani,
                    circondata da vigneti ricchi di tradizione.
                    Produciamo vini di altissima qualità seguendo
                    metodi artigianali tramandati da generazioni.
                </p>
            </div>
        </div>
    </div>

    <!-- SEZIONE 2 -->
    <div class="tenuta-section bg-wine">
        <div class="tenuta-container reverse">
            <div class="tenuta-info-col">
                <h2>Tenuta Valle Dorata</h2>
                <p>
                    Situata in una valle soleggiata, questa tenuta è specializzata
                    in vitigni autoctoni, con una grande attenzione alla sostenibilità
                    e al rispetto del territorio.
                </p>
            </div>
            <div class="tenuta-img-col">
                <img src="{{ asset('img/tenute/seconda_tenuta.jpg') }}" alt="Tenuta Valle Dorata">
            </div>
        </div>
    </div>

</div>

@endsection

