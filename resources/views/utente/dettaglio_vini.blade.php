@extends('base')

@section('title', config('app.name') . ' - Dettaglio vino')

@section('content')
<h1>{{ $prodotto->nome }}</h1>


@endsection