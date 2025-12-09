<div class="modal fade" id="vinoModal" tabindex="-1" aria-labelledby="vinoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="vinoForm" method="POST">
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
                               class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Prezzo</label>
                        <input type="number" step="0.01" name="prezzo" id="vino-prezzo"
                               class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Annata</label>
                        <input type="number" name="annata" id="vino-annata"
                               class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Formato</label>
                        <input type="text" name="formato" id="vino-formato"
                               class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gradazione</label>
                        <input type="number" step="0.1" name="gradazione" id="vino-gradazione"
                               class="form-control" required>
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
