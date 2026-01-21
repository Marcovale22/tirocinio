@extends('base') 

@section('title', config('app.name') . ' - Crea evento')

@section('content')
<div class="pagina-edit-evento">
    <div class="container py-4">

        <h2 class="mb-4 admin-edit-title">Crea nuovo evento</h2>

        {{-- Messaggio successo --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Errori generali --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Attenzione:</strong> controlla i campi evidenziati.
            </div>
        @endif
        <div class="admin-edit-wrap">
        <form method="POST" action="{{ route('catalogo.store.evento') }}" enctype="multipart/form-data">
            @csrf

            {{-- Nome --}}
            <div class="mb-3">
                <label class="form-label">Nome evento</label>
                <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                    value="{{ old('nome') }}">
                @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Prezzo --}}
            <div class="mb-3">
                <label class="form-label">Prezzo</label>
                <input type="number" step="0.01" name="prezzo"
                    class="form-control @error('prezzo') is-invalid @enderror"
                    value="{{ old('prezzo') }}">
                @error('prezzo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Disponibilità (posti) --}}
            <div class="mb-3">
                <label class="form-label">Disponibilità (posti)</label>
                <input type="number" name="disponibilita"
                    class="form-control @error('disponibilita') is-invalid @enderror"
                    value="{{ old('disponibilita', 0) }}">
                @error('disponibilita') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                {{-- Data evento --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Data evento</label>
                    <input type="date" name="data_evento"
                        class="form-control @error('data_evento') is-invalid @enderror"
                        min="{{ now()->format('Y-m-d') }}"
                        value="{{ old('data_evento') }}">
                    @error('data_evento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Ora evento --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ora evento</label>
                    <input type="time" name="ora_evento" placeholder="Es. 18:30"
                        class="form-control @error('ora_evento') is-invalid @enderror"
                        value="{{ old('ora_evento') }}">
                    @error('ora_evento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- Luogo --}}
            <div class="mb-3">
                <label class="form-label">Luogo</label>
                <input type="text" name="luogo"
                    class="form-control @error('luogo') is-invalid @enderror"
                    value="{{ old('luogo') }}">
                @error('luogo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Descrizione --}}
            <div class="mb-3">
                <label class="form-label">Descrizione (opzionale)</label>
                <textarea name="descrizione" rows="4"
                        class="form-control @error('descrizione') is-invalid @enderror">{{ old('descrizione') }}</textarea>
                @error('descrizione') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Immagine --}}
            <div class="mb-3">
                <label class="form-label">Immagine (opzionale)</label>
                <input type="file" name="immagine"
                    class="form-control @error('immagine') is-invalid @enderror">
                @error('immagine') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div class="form-text">Formati supportati: JPG, JPEG, PNG, WEBP.</div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn-success">Crea evento</button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Annulla</a>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection
