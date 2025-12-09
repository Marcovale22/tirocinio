@extends('base')

@section('title', config('app.name') . ' - Dipendenti')

@section('content')
    <div class="pagina-dipendenti py-4">
        <div class="container">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="titolo-dipendenti mb-0">Dipendenti</h1>

                {{-- Pulsante aggiungi --}}
                <button type="button" class="btn-add-staff" data-bs-toggle="modal" data-bs-target="#addDipendenteModal">
                     + Aggiungi dipendente
                </button>

            </div>

            {{-- Messaggio di successo eventuale --}}
            @if (session('success'))
                <div class="alert alert-success auto-dismiss-alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger auto-dismiss-alert">
                    {{ session('error') }}
                </div>
            @endif


            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 align-middle table-staff">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>Username</th>
                                <th>E-mail</th>
                                <th>Data di nascita</th>
                                <th class="text-end">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dipendenti as $dipendente)
                                <tr>
                                    <td>{{ $dipendente->name }}</td>
                                    <td>{{ $dipendente->username }}</td>
                                    <td>{{ $dipendente->email }}</td>
                                    <td>{{ $dipendente->data_di_nascita ? \Carbon\Carbon::parse($dipendente->data_di_nascita)->format('d-m-Y') : '' }}</td>
                                    <td class="text-end">
                                        {{-- Modifica --}}
                                        <button type="button"
                                                class="btn btn-sm btn-warning btn-edit-dipendente"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editDipendenteModal"
                                                data-id="{{ $dipendente->id }}"
                                                data-name="{{ $dipendente->name }}"
                                                data-username="{{ $dipendente->username }}"
                                                data-email="{{ $dipendente->email }}"
                                                data-nascita="{{ $dipendente->data_di_nascita }}">
                                            Modifica
                                        </button>

                                        {{-- Elimina --}}
                                        <form action="{{ route('dipendenti.destroy', $dipendente->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Sei sicuro di voler eliminare questo dipendente?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Elimina
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        Nessun dipendente presente.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
<!-- Modal aggiungi dipendente -->
<div class="modal fade" id="addDipendenteModal" tabindex="-1" aria-labelledby="addDipendenteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('dipendenti.store') }}" method="POST">
                @csrf
                <input type="hidden" name="form_type" value="add">

                <div class="modal-header">
                    <h5 class="modal-title" id="addDipendenteLabel">Nuovo dipendente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text"
                               name="username"
                               class="form-control @error('username') is-invalid @enderror"
                               value="{{ old('username') }}"
                               required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">E-mail</label>
                        <input type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Data di nascita</label>
                        <input type="date"
                            name="data_di_nascita"
                            class="form-control @error('data_di_nascita') is-invalid @enderror"
                            value="{{ old('data_di_nascita') }}"
                            min="1900-01-01"
                            max="{{ now()->toDateString() }}">
                        @error('data_di_nascita')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Conferma password</label>
                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary">Salva</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal modifica dipendente -->
<div class="modal fade" id="editDipendenteModal" tabindex="-1" aria-labelledby="editDipendenteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editDipendenteForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="form_type" value="edit">

                <div class="modal-header">
                    <h5 class="modal-title" id="editDipendenteLabel">Modifica dipendente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text"
                               name="name"
                               id="edit-name"
                               class="form-control @error('name') is-invalid @enderror"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text"
                               name="username"
                               id="edit-username"
                               class="form-control @error('username') is-invalid @enderror"
                               required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">E-mail</label>
                        <input type="email"
                               name="email"
                               id="edit-email"
                               class="form-control @error('email') is-invalid @enderror"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Data di nascita</label>
                        <input type="date"
                            name="data_di_nascita"
                            id="edit-data-di-nascita"
                            class="form-control @error('data_di_nascita') is-invalid @enderror"
                            min="1900-01-01"
                            max="{{ now()->toDateString() }}">
                        @error('data_di_nascita')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <hr>

                    <p class="mb-1"><strong>Password</strong> (lascia vuoto se non vuoi cambiarla)</p>

                    <div class="mb-3">
                        <label class="form-label">Nuova password</label>
                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Conferma nuova password</label>
                        <input type="password"
                               name="password_confirmation"
                               class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary">Salva modifiche</button>
                </div>
            </form>
        </div>
    </div>
</div>



@if ($errors->any())
    @if (old('form_type') === 'add')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var myModal = new bootstrap.Modal(document.getElementById('addDipendenteModal'));
                myModal.show();
            });
        </script>
    @elseif (old('form_type') === 'edit')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var myModal = new bootstrap.Modal(document.getElementById('editDipendenteModal'));
                myModal.show();
            });
        </script>
    @endif
@endif


<script>
document.addEventListener('DOMContentLoaded', function () {
    var editModal = document.getElementById('editDipendenteModal');
    if (!editModal) return;

    editModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;

        var id       = button.getAttribute('data-id');
        var name     = button.getAttribute('data-name');
        var username = button.getAttribute('data-username');
        var email    = button.getAttribute('data-email');
        var nascita  = button.getAttribute('data-nascita');

        document.getElementById('edit-name').value            = name ?? '';
        document.getElementById('edit-username').value        = username ?? '';
        document.getElementById('edit-email').value           = email ?? '';
        document.getElementById('edit-data-di-nascita').value = nascita ?? '';

        // ACTION DELL'UPDATE
        var form = document.getElementById('editDipendenteForm');
        var action = "{{ route('dipendenti.update', ':id') }}";
        form.action = action.replace(':id', id);
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const alerts = document.querySelectorAll('.auto-dismiss-alert');

    alerts.forEach((alertEl) => {
        setTimeout(() => {
            // se vuoi solo farlo sparire di colpo:
            // alertEl.style.display = 'none';

            // se vuoi una piccola animazione di fade:
            alertEl.style.transition = 'opacity 0.5s ease';
            alertEl.style.opacity = '0';

            setTimeout(() => {
                alertEl.remove();
            }, 500); // tempo della transizione
        }, 3000); // 3 secondi prima di iniziare a sparire
    });
});
</script>


@endsection
