@extends('base')

@section('title', config('app.name') . ' - Richieste vigneto (Staff)')

@section('content')
<div class="staff-richieste">
<div class="container py-4 staff-richieste-vigneto">
    <h2 class="staff-title">Richieste affitto vigneto</h2>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="table-responsive mt-3 staff-table-wrap">
        <table class="table table-hover align-middle staff-table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Utente</th>
                    <th>Vigneto</th>
                    <th>Annata</th>
                    <th>Stato</th>
                    <th class="text-end">Azioni</th>
                </tr>
            </thead>
            <tbody>
            @forelse($richieste as $r)
                <tr>
                    <td>{{ $r->created_at->format('d/m/Y') }}</td>
                    <td>{{ $r->user->name ?? $r->user->email }}</td>
                    <td>{{ $r->vigneto->nome }}</td>
                    <td>{{ $r->annata }}</td>
                    <td>
                        <span class="badge-stato {{ 'stato-'.$r->stato }}">
                            {{ $r->stato }}
                        </span>
                    </td>
                    <td class="text-end">
                        @if($r->stato === 'in_attesa')
                            <form class="d-inline" method="POST" action="{{ route('staff.vigneti.richieste.conferma', $r) }}">
                                @csrf
                                <button class="btn btn-sm btn-success">Conferma</button>
                            </form>

                            <form class="d-inline" method="POST" action="{{ route('staff.vigneti.richieste.rifiuta', $r) }}">
                                @csrf
                                <button class="btn btn-sm btn-danger">Rifiuta</button>
                            </form>
                        @else
                            —
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Nessuna richiesta.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
@endsection
