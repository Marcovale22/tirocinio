@extends('base')

@section('title', 'Gestione prenotazioni')

@section('content')
<div class="prenotazioni-staff">
    <div class="container py-4">


        <div class="staff-prenotazioni-header mb-4">
            <h1>Prenotazioni</h1>
            <p>Gestione delle prenotazioni degli eventi</p>
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

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Utente</th>
                            <th>Evento</th>
                            <th>Data/Ora</th>
                            <th>Posti</th>
                            <th>Stato corrente</th>
                            <th>Modifica Stato</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($prenotazioni as $p)
                        @php
                            $evento   = $p->evento;
                            $prodotto = $evento?->prodotto;
                        @endphp

                        <tr>

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

                            <td>{{ $p->stato }}</td>

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
                                    <div class="wrapper-btn-prenotazioni">
                                        <button class="btn-salva-prenotazioni-staff"
                                        onclick="return confirm('Vuoi confermare la modifca dello stato della prenotazione?')">Salva</button>
                                    </div>
                                </form>
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

       
        </div>
    </div>
</div>
@endsection
