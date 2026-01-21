<div class="modal fade" id="merchModal" tabindex="-1" aria-labelledby="merchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="merchForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="merchFormMethod" value="POST">
                <input type="hidden" id="merch-id" name="merch_id" value="{{ old('merch_id') }}">
                <input type="hidden" id="merch-mode" name="merch_mode" value="{{ old('merch_mode', 'create') }}">

                <div class="modal-header">
                    <h5 class="modal-title" id="merchModalLabel">Nuovo prodotto merch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" name="nome_merch" id="merch-nome"
                            class="form-control @error('nome_merch') is-invalid @enderror"
                            value="{{ old('nome_merch') }}" required>
                        @error('nome_merch') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Prezzo</label>
                        <input type="number" step="0.01" name="prezzo_merch" id="merch-prezzo"
                            class="form-control @error('prezzo_merch') is-invalid @enderror"
                            value="{{ old('prezzo_merch') }}" required>
                        @error('prezzo_merch') <div class="invalid-feedback">{{ $message }}</div> @enderror

                    </div>

                    <div class="mb-3">
                        <label class="form-label">Disponibilità</label>
                        <input type="number" name="disponibilita_merch" id="merch-disponibilita"
                            class="form-control @error('disponibilita_merch') is-invalid @enderror"
                            value="{{ old('disponibilita_merch') }}" min="0">
                        @error('disponibilita_merch') <div class="invalid-feedback">{{ $message }}</div> @enderror

                    </div>


                    <div class="mb-3">
                        <label class="form-label">Immagine</label>
                        <input type="file" name="immagine" id="merch-immagine"
                            class="form-control">
                        <small class="text-muted">Se non carichi nulla verrà usato un placeholder.</small>
                    </div>


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary" id="merch-submit-btn">Salva</button>
                </div>
            </form>
        </div>
    </div>
</div>
