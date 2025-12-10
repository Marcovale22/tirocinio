<div class="modal fade" id="vinoModal" tabindex="-1" aria-labelledby="vinoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="vinoForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="vinoFormMethod" value="POST">

                <div class="modal-header">
                    <h5 class="modal-title" id="vinoModalLabel">Nuovo vino</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" name="nome" id="vino-nome"
                               class="form-control @error('nome') is-invalid @enderror"
                               value="{{ old('nome') }}" required>
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Prezzo</label>
                        <input type="number" step="0.01" name="prezzo" id="vino-prezzo"
                               class="form-control @error('prezzo') is-invalid @enderror"
                               value="{{ old('prezzo') }}" required>
                        @error('prezzo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Annata</label>
                        <input type="number" name="annata" id="vino-annata"
                               class="form-control @error('annata') is-invalid @enderror"
                               value="{{ old('annata') }}" required>
                        @error('annata')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Formato</label>
                        <input type="text" name="formato" id="vino-formato"
                               class="form-control @error('formato') is-invalid @enderror"
                               value="{{ old('formato') }}" required>
                        @error('formato')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gradazione</label>
                        <input type="number" step="0.1" name="gradazione" id="vino-gradazione"
                               class="form-control @error('gradazione') is-invalid @enderror"
                               value="{{ old('gradazione') }}" required>
                        @error('gradazione')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Disponibilità</label>
                        <input type="number" min="0" name="disponibilita" id="vino-disponibilita"
                               class="form-control @error('disponibilita') is-invalid @enderror"
                               value="{{ old('disponibilita') }}">
                        @error('disponibilita')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Se lasci vuoto, verrà impostato a 0.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Solfiti</label>
                        <input type="number" step="0.1" min="0" name="solfiti" id="vino-solfiti"
                               class="form-control @error('solfiti') is-invalid @enderror"
                               value="{{ old('solfiti') }}">
                        @error('solfiti')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Se lasci vuoto, verrà impostato a 0.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Immagine</label>
                        <input type="file" name="immagine"
                            class="form-control @error('immagine') is-invalid @enderror">
                        @error('immagine')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Se non carichi nulla verrà usata l'immagine placeholder.
                        </small>
                    </div>
                    
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary" id="vino-submit-btn">Salva</button>
                </div>
            </form>
        </div>
    </div>
</div>
