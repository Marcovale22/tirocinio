<div class="modal fade" id="vignetoModal" tabindex="-1" aria-labelledby="vignetoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="vignetoForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="vignetoFormMethod" value="POST">

                <div class="modal-header">
                    <h5 class="modal-title" id="vignetoModalLabel">Nuovo vigneto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text"
                               name="nome"
                               id="vigneto-nome"
                               class="form-control @error('nome') is-invalid @enderror"
                               value="{{ old('nome') }}"
                               required>
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descrizione</label>
                        <textarea name="descrizione"
                                  id="vigneto-descrizione"
                                  rows="3"
                                  class="form-control @error('descrizione') is-invalid @enderror">{{ old('descrizione') }}</textarea>
                        @error('descrizione')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Disponibilità</label>
                        <input type="number"
                               name="disponibilita"
                               id="vigneto-disponibilita"
                               min="0"
                               class="form-control @error('disponibilita') is-invalid @enderror"
                               value="{{ old('disponibilita') }}">
                        @error('disponibilita')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Se vuoto, verrà impostato a 0.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Prezzo annuo</label>
                        <input type="number"
                               step="0.01"
                               name="prezzo_annuo"
                               id="vigneto-prezzo-annuo"
                               class="form-control @error('prezzo_annuo') is-invalid @enderror"
                               value="{{ old('prezzo_annuo') }}"
                               required>
                        @error('prezzo_annuo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Immagine</label>
                        <input type="file"
                               name="immagine"
                               id="vigneto-immagine"
                               class="form-control @error('immagine') is-invalid @enderror">
                        @error('immagine')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Se non carichi nulla verrà usato un placeholder.</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary" id="vigneto-submit-btn">Salva</button>
                </div>
            </form>
        </div>
    </div>
</div>
