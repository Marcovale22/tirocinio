<div class="modal fade" id="eventoModal" tabindex="-1" aria-labelledby="eventoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="eventoForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="eventoFormMethod" value="POST">

                <div class="modal-header">
                    <h5 class="modal-title" id="eventoModalLabel">Nuovo evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" name="nome" id="evento-nome" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Prezzo</label>
                        <input type="number" step="0.01" name="prezzo" id="evento-prezzo" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Data evento</label>
                        <input type="date" name="data_evento" id="evento-data" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ora evento</label>
                        <input type="time" name="ora_evento" id="evento-ora" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Disponibilità</label>
                        <input type="number" name="disponibilita" id="evento-disponibilita" class="form-control" required>
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
