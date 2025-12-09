<div class="modal fade" id="vignetoModal" tabindex="-1" aria-labelledby="vignetoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="vignetoForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="vignetoFormMethod" value="POST">

                <div class="modal-header">
                    <h5 class="modal-title" id="vignetoModalLabel">Nuovo vigneto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" name="nome" id="vigneto-nome" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descrizione</label>
                        <textarea name="descrizione" id="vigneto-descrizione" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Disponibilità totale</label>
                        <input type="number" name="disponibilita_totale" id="vigneto-disponibilita" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Prezzo annuo (€)</label>
                        <input type="number" step="0.01" name="prezzo_annuo" id="vigneto-prezzo" class="form-control" required>
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
