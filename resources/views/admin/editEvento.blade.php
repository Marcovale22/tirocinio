@extends('base') 

@section('title', config('app.name') . ' - Modifica evento')

@section('content')
@php
    $fromCreate = session('from_create', false);
@endphp
<div class="pagina-edit-evento">
    <div class="container py-4">

        <h2 class="mb-4 admin-edit-title">Modifica evento: {{ $prod->nome }}</h2>


        {{-- Messaggio successo --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Errori generali --}}
        @if($errors->any())
            <div class="alert alert-danger">
                Controlla i campi.
            </div>
        @endif

        <div class="admin-edit-wrap">
            <form method="POST"
            action="{{ route('catalogo.update.evento', $prod->id) }}"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- ================= DATI EVENTO ================= --}}
            <h4 class="admin-section-title">Dati evento</h4>

            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text"
                    name="nome"
                    class="form-control @error('nome') is-invalid @enderror"
                    value="{{ old('nome', $prod->nome) }}">
                @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Prezzo</label>
                    <input type="number" step="0.01"
                        name="prezzo"
                        class="form-control @error('prezzo') is-invalid @enderror"
                        value="{{ old('prezzo', $prod->prezzo) }}">
                    @error('prezzo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Disponibilità posti</label>
                    <input type="number"
                        name="disponibilita"
                        class="form-control @error('disponibilita') is-invalid @enderror"
                        value="{{ old('disponibilita', $prod->disponibilita) }}">
                    @error('disponibilita') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Data evento</label>
                <input type="date"
                    name="data_evento"
                    class="form-control @error('data_evento') is-invalid @enderror"
                    min="{{ now()->format('Y-m-d') }}"
                    value="{{ old('data_evento', \Carbon\Carbon::parse($evento->data_evento)->format('Y-m-d')) }}">
                @error('data_evento')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


                <div class="col-md-6 mb-3">
                    <label class="form-label">Ora evento</label>
                    <input type="time"
                        name="ora_evento"
                        class="form-control @error('ora_evento') is-invalid @enderror"
                        value="{{ old('ora_evento', $evento->ora_evento) }}">
                    @error('ora_evento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Luogo</label>
                <input type="text"
                    name="luogo"
                    class="form-control @error('luogo') is-invalid @enderror"
                    value="{{ old('luogo', $evento->luogo) }}">
                @error('luogo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Descrizione</label>
                <textarea name="descrizione"
                        rows="4"
                        class="form-control @error('descrizione') is-invalid @enderror">{{ old('descrizione', $evento->descrizione) }}</textarea>
                @error('descrizione') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Immagine</label>
                <input type="file" name="immagine" class="form-control">
                @error('immagine') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <hr class="admin-divider">  

            {{-- ================= VINI ASSOCIATI ================= --}}
            <h4 class="admin-section-title">Vini associati all’evento</h4>

            {{-- errore generale sui vini (es. dal controller/sync) --}}
            @error('vini')
            <div class="alert alert-danger mt-3">{{ $message }}</div>
            @enderror

            <table class="table" id="vini-table">
                <thead>
                    <tr>
                        <th>Vino</th>
                        <th style="width:150px">Quantità</th>
                        <th style="width:120px"></th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $righe = old('vini') ?? $evento->vini->map(fn($v) => [
                            'vino_id' => $v->id,
                            'quantita' => $v->pivot->quantita
                        ])->values()->toArray();
                    @endphp

                    @foreach($righe as $i => $r)
                        @php
                            $rowHasError = $errors->has("vini.$i.vino_id") || $errors->has("vini.$i.quantita");
                        @endphp

                        <tr class="{{ $rowHasError ? 'row-error' : '' }}">
                            <td>
                                <select name="vini[{{ $i }}][vino_id]"
                                        class="form-select @error("vini.$i.vino_id") is-invalid @enderror">
                                    @foreach($vini as $vino)
                                        <option value="{{ $vino->id }}"
                                            @selected($vino->id == ($r['vino_id'] ?? null))>
                                            {{ $vino->prodotto->nome }}
                                            (disp: {{ $vino->prodotto->disponibilita }})
                                        </option>
                                    @endforeach
                                </select>

                                @error("vini.$i.vino_id")
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </td>

                            <td>
                                <input type="number"
                                    min="1"
                                    name="vini[{{ $i }}][quantita]"
                                    class="form-control @error("vini.$i.quantita") is-invalid @enderror"
                                    value="{{ $r['quantita'] ?? 1 }}">

                                @error("vini.$i.quantita")
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </td>

                            <td class="text-end">
                                <button type="button"
                                        class="btn btn-outline-danger btn-sm remove-row">
                                    Rimuovi
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


            <button type="button" id="add-vino" class="btn btn-outline-primary">
                + Aggiungi vino
            </button>

            {{-- ================= PULSANTI FINALI ================= --}}
            <div class="admin-actions">
                <button type="submit" class="btn-success">
                    Salva modifiche
                </button>

                @if($fromCreate)
                    <a href="{{ route('catalogo.index') }}"
                    class="btn btn-outline-secondary">
                        Assegna vini più tardi
                    </a>
                @else
                    <a href="{{ route('catalogo.index') }}"
                    class="btn btn-secondary">
                        Torna al catalogo
                    </a>
                @endif
            </div>

            </form>
        </div>
    </div>
</div>
<script id="vini-json" type="application/json">
{!! json_encode($vini->map(function($v){
    return [
        'id' => $v->id,
        'label' => ($v->prodotto->nome ?? 'Vino') . ' (disp: ' . ($v->prodotto->disponibilita ?? 0) . ')'
    ];
})->values()) !!}
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tbody = document.querySelector('#vini-table tbody');
    const addBtn = document.querySelector('#add-vino');

    const jsonEl = document.getElementById('vini-json');
    const vini = JSON.parse(jsonEl.textContent);

    function renumber() {
        [...tbody.querySelectorAll('tr')].forEach((tr, i) => {
            tr.querySelector('select').name = `vini[${i}][vino_id]`;
            tr.querySelector('input').name  = `vini[${i}][quantita]`;
        });
    }

    addBtn.addEventListener('click', () => {
        const tr = document.createElement('tr');
        const options = vini.map(v => `<option value="${v.id}">${v.label}</option>`).join('');

        tr.innerHTML = `
            <td><select class="form-select">${options}</select></td>
            <td><input type="number" min="1" class="form-control" value="1"></td>
            <td class="text-end">
                <button type="button" class="btn btn-outline-danger btn-sm remove-row">Rimuovi</button>
            </td>
        `;

        tbody.appendChild(tr);
        renumber();
    });

    tbody.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
            renumber();
        }
    });
});
</script>


@endsection
