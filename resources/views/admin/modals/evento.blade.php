<div class="modal fade" id="eventoModal" tabindex="-1" aria-labelledby="eventoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="eventoForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="eventoFormMethod" value="POST">

                <div class="modal-header">
                    <h5 class="modal-title" id="eventoModalLabel">Nuovo evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text"
                               name="nome"
                               id="evento-nome"
                               class="form-control @error('nome') is-invalid @enderror"
                               value="{{ old('nome') }}"
                               required>
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Prezzo</label>
                        <input type="number"
                               step="0.01"
                               name="prezzo"
                               id="evento-prezzo"
                               class="form-control @error('prezzo') is-invalid @enderror"
                               value="{{ old('prezzo') }}"
                               required>
                        @error('prezzo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Data evento</label>
                        <input type="date"
                               name="data_evento"
                               id="evento-data"
                               class="form-control @error('data_evento') is-invalid @enderror"
                               value="{{ old('data_evento') }}"
                               required>
                        @error('data_evento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ora evento</label>
                        <input type="time"
                               name="ora_evento"
                               id="evento-ora"
                               class="form-control @error('ora_evento') is-invalid @enderror"
                               value="{{ old('ora_evento') }}"
                               required>
                        @error('ora_evento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Disponibilità</label>
                        <input type="number"
                               name="disponibilita"
                               id="evento-disponibilita"
                               class="form-control @error('disponibilita') is-invalid @enderror"
                               value="{{ old('disponibilita') }}"
                               min="0"
                               required>
                        @error('disponibilita')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descrizione</label>
                        <textarea name="descrizione"
                                id="evento-descrizione"
                                class="form-control @error('descrizione') is-invalid @enderror"
                                rows="3">{{ old('descrizione') }}</textarea>
                        @error('descrizione')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- CAMPO FILE --}}
                    <div class="mb-3">
                        <label class="form-label">Immagine evento</label>
                        <input type="file"
                               name="immagine"
                               id="evento-immagine"
                               class="form-control @error('immagine') is-invalid @enderror">
                        @error('immagine')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Se non carichi nulla verrà usato un placeholder.
                        </small>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary" id="evento-submit-btn">Salva</button>
                </div>
            </form>
        </div>
    </div>
</div>
