<script>
document.addEventListener('DOMContentLoaded', function () {
    const merchModal = document.getElementById('merchModal');
    const merchForm  = document.getElementById('merchForm');
    const methodInp  = document.getElementById('merchFormMethod');
    const titleEl    = document.getElementById('merchModalLabel');
    const submitBtn  = document.getElementById('merch-submit-btn');

    if (!merchModal) return;

    merchModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        // Se il modal è aperto da JS (dopo errore) non c'è un bottone → non tocco i campi
        if (!button) return;

        const mode = button.getAttribute('data-mode'); // create | edit

        if (mode === 'create') {
            // Modal in modalità AGGIUNTA
            titleEl.textContent   = 'Nuovo prodotto merch';
            submitBtn.textContent = 'Crea';

            merchForm.action = "{{ route('catalogo.store.merch') }}";
            methodInp.value  = 'POST';

            // pulisco i campi
            document.getElementById('merch-nome').value           = '';
            document.getElementById('merch-prezzo').value         = '';
            const disp = document.getElementById('merch-disponibilita');
            if (disp) disp.value = '';
            // il file input NON si tocca via JS, va bene così
        }

        if (mode === 'edit') {
            // Modal in modalità MODIFICA
            titleEl.textContent   = 'Modifica merch';
            submitBtn.textContent = 'Salva modifiche';

            const id            = button.getAttribute('data-id');
            const nome          = button.getAttribute('data-nome') || '';
            const prezzo        = button.getAttribute('data-prezzo') || '';
            const disponibilita = button.getAttribute('data-disponibilita') || '';

            merchForm.action = "{{ route('catalogo.update.merch', ':id') }}".replace(':id', id);
            methodInp.value  = 'PUT';

            document.getElementById('merch-nome').value           = nome;
            document.getElementById('merch-prezzo').value         = prezzo;
            const disp = document.getElementById('merch-disponibilita');
            if (disp) disp.value = disponibilita;
        }
    });
});
</script>
