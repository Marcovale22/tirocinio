<div class="modal fade" id="merchModal" tabindex="-1" aria-labelledby="merchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="merchForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="merchFormMethod" value="POST">

                <div class="modal-header">
                    <h5 class="modal-title" id="merchModalLabel">Nuovo prodotto merch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" name="nome" id="merch-nome" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Prezzo</label>
                        <input type="number" step="0.01" name="prezzo" id="merch-prezzo" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Disponibilità</label>
                        <input type="number" name="disponibilita" id="merch-disponibilita"
                            class="form-control" min="0">
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
