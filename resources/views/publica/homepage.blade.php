@extends('base')

@section('title', config('app.name') . ' - Homepage')

@section('content')
<section class="home-hero home-hero-slideshow">
    <div class="slide s1"></div>
    <div class="slide s2"></div>
    <div class="slide s3"></div>

    <div class="home-hero-overlay"></div>

    <div class="home-hero-content">
        <h1>Benvenuto in Bellò</h1>
        <p>Scopri vini, vigneti ed eventi in cantina.</p>
        <div class="home-hero-cta">
        <a href="{{ route('vini') }}" class="btn-home">Esplora i vini</a>
        </div>
    </div>
</section>

@endsection