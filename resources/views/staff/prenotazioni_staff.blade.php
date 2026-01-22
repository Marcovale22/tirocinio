@extends('base')

@section('title', 'Gestione prenotazioni')

@section('content')
<div class="container py-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="mb-0">Prenotazioni</h1>
            <p class="text-muted mb-0">Gestisci lo stato delle prenotazioni eventi.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Utente</th>
                        <th>Evento</th>
                        <th>Data/Ora</th>
                        <th>Posti</th>
                        <th>Stato</th>
                        <th class="text-end">Azione</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($prenotazioni as $p)
                    @php
                        $evento   = $p->evento;
                        $prodotto = $evento?->prodotto;
                    @endphp

                    <tr>
                        <td>#{{ $p->id }}</td>

                        <td>
                            <div class="fw-semibold">{{ $p->utente->name ?? 'Utente' }}</div>
                            <div class="text-muted small">{{ $p->utente->email ?? '' }}</div>
                        </td>

                        <td>
                            <div class="fw-semibold">{{ $prodotto?->nome ?? 'Evento' }}</div>
                            <div class="text-muted small">{{ $evento->luogo ?? '' }}</div>
                        </td>

                        <td>
                            @if($evento)
                                {{ \Carbon\Carbon::parse($evento->data_evento)->format('d/m/Y') }}
                                {{ \Carbon\Carbon::parse($evento->ora_evento)->format('H:i') }}
                            @endif
                            <div class="text-muted small">{{ $p->created_at->format('d/m/Y H:i') }}</div>
                        </td>

                        <td>{{ $p->posti }}</td>

                        <td>
                            {{-- select stato --}}
                            <form action="{{ route('staff.prenotazioni.updateStato', $p->id) }}" method="POST" class="d-flex gap-2 align-items-center">
                                @csrf
                                @method('PATCH')

                                <select name="stato" class="form-select form-select-sm" style="max-width: 160px;">
                                    <option value="in_attesa"  @selected($p->stato==='in_attesa')>In attesa</option>
                                    <option value="confermata" @selected($p->stato==='confermata')>Confermata</option>
                                    <option value="annullata"  @selected($p->stato==='annullata')>Annullata</option>
                                </select>

                                <button class="btn btn-sm btn-primary">Salva</button>
                            </form>
                        </td>

                        <td class="text-end">
                            {{-- spazio per future azioni (dettaglio, ecc.) --}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Nessuna prenotazione trovata.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $prenotazioni->links() }}
    </div>

</div>
@endsection
