<script>
document.addEventListener('DOMContentLoaded', function () {
    const vignetoModal = document.getElementById('vignetoModal');
    const vignetoForm  = document.getElementById('vignetoForm');
    const methodInp    = document.getElementById('vignetoFormMethod');
    const titleEl      = document.getElementById('vignetoModalLabel');
    const submitBtn    = document.getElementById('vigneto-submit-btn');

    if (!vignetoModal) return;

    vignetoModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        // Se il modal si apre dopo errore via JS, nessun bottone → non tocco i campi
        if (!button) return;

        const mode = button.getAttribute('data-mode'); // create | edit

        if (mode === 'create') {
            titleEl.textContent   = 'Nuovo vigneto';
            submitBtn.textContent = 'Crea';

            vignetoForm.action = "{{ route('catalogo.store.vigneto') }}";
            methodInp.value    = 'POST';

            // pulisco i campi
            document.getElementById('vigneto-nome').value           = '';
            document.getElementById('vigneto-descrizione').value    = '';
            document.getElementById('vigneto-disponibilita').value  = '';
            document.getElementById('vigneto-prezzo-annuo').value   = '';
            // il file input non si tocca via JS
        }

        if (mode === 'edit') {
            titleEl.textContent   = 'Modifica vigneto';
            submitBtn.textContent = 'Salva modifiche';

            const id            = button.getAttribute('data-id');
            const nome          = button.getAttribute('data-nome') || '';
            const descrizione   = button.getAttribute('data-descrizione') || '';
            const disponibilita = button.getAttribute('data-disponibilita') || '';
            const prezzoAnnuale = button.getAttribute('data-prezzo-annuo') || '';

            vignetoForm.action = "{{ route('catalogo.update.vigneto', ':id') }}".replace(':id', id);
            methodInp.value    = 'PUT';

            document.getElementById('vigneto-nome').value           = nome;
            document.getElementById('vigneto-descrizione').value    = descrizione;
            document.getElementById('vigneto-disponibilita').value  = disponibilita;
            document.getElementById('vigneto-prezzo-annuo').value   = prezzoAnnuale;
        }
    });
});
</script>
