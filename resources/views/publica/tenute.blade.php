@extends('base')

@section('title', config('app.name') . ' - Tenute')

@section('content')

<div class="tenute-wrapper">

    <!-- SEZIONE 1 - SFONDO ARANCIO -->
    <div class="tenuta-section bg-orange first-section">

        <h1 class="titolo-tenute">Le Tenute</h1>

        <div class="tenuta-container">
            <div class="tenuta-img-col">
                <img src="{{ asset('img/tenute/prima_tenuta.jpg') }}" alt="Tenuta San Lorenzo">
            </div>

            <div class="tenuta-info-col">
                <h2>Tenuta Monsampietro</h2>
                <p>
                    Una storica tenuta immersa nei colli marchigiani,
                    circondata da vigneti ricchi di tradizione.
                    Produciamo vini di altissima qualità seguendo
                    metodi artigianali tramandati da generazioni.
                </p>
            </div>
        </div>

        {{-- VIENI A TROVARCI (ARANCIO) --}}
        <div class="visit-us">
            <h2 class="visit-title">Vieni a trovarci</h2>

            <div class="map-wrap">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2922.2390944614694!2d13.50495620210092!3d42.9099982260252!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x132e0227cf7b3023%3A0xf17c5a18951f35fe!2s63091%20Monsampietro%20AP!5e0!3m2!1sit!2sit!4v1768294871306!5m2!1sit!2sit"
                    width="100%" height="360"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

    </div>

    <!-- SEZIONE 2 - SFONDO NERO -->
    <div class="tenuta-section bg-wine">
        <div class="tenuta-container reverse">
            <div class="tenuta-info-col">
                <h2>Tenuta Appignano del tronto</h2>
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

        {{-- VIENI A TROVARCI (NERO) --}}
        <div class="visit-us">
            <h2 class="visit-title">Vieni a trovarci</h2>

            <div class="map-wrap">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2923.5299533860566!2d13.68136765828683!3d42.882764394144765!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1331fbd2cc9d6d6b%3A0x46f6fc0c8cdd0225!2sContrada%20Valle%20S.%20Martino%2C%2021%2C%2063083%20Valle%20San%20Martino%20I%20AP!5e0!3m2!1sit!2sit!4v1768295857866!5m2!1sit!2sit"
                    width="100%" height="360"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

    </div>

</div>

@endsection
