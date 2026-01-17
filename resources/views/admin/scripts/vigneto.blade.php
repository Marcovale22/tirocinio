<script>
document.addEventListener('DOMContentLoaded', function () {
    const vignetoModal = document.getElementById('vignetoModal');
    const vignetoForm  = document.getElementById('vignetoForm');
    const methodInp    = document.getElementById('vignetoFormMethod');
    const titleEl      = document.getElementById('vignetoModalLabel');
    const submitBtn    = document.getElementById('vigneto-submit-btn');
    const bottiglieInp = document.getElementById('vigneto-bottiglie-stimate');
    const tipoVinoSel  = document.getElementById('vigneto-tipo-vino');
    const faseSel      = document.getElementById('vigneto-fase');

    const visibileChk  = document.getElementById('vigneto-visibile');

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

            // default: visibile attivo
            if (visibileChk) visibileChk.checked = true;
            if (bottiglieInp) bottiglieInp.value = '';
            if (tipoVinoSel)  tipoVinoSel.value = '';
            if (faseSel)      faseSel.value = '';
        }

        if (mode === 'edit') {
            titleEl.textContent   = 'Modifica vigneto';
            submitBtn.textContent = 'Salva modifiche';

            const id            = button.getAttribute('data-id');
            const nome          = button.getAttribute('data-nome') || '';
            const descrizione   = button.getAttribute('data-descrizione') || '';
            const disponibilita = button.getAttribute('data-disponibilita') || '';
            const prezzoAnnuale = button.getAttribute('data-prezzo-annuo') || '';
            const bottiglie = button.getAttribute('data-bottiglie') || '';
            const tipoVino  = button.getAttribute('data-tipo-vino') || '';
            const fase      = button.getAttribute('data-fase') || '';

            if (bottiglieInp) bottiglieInp.value = bottiglie;
            if (tipoVinoSel)  tipoVinoSel.value = tipoVino;
            if (faseSel)      faseSel.value = fase;


            // <-- aggiunto
            const visibileAttr  = button.getAttribute('data-visibile'); // "1" / "0" / null

            vignetoForm.action = "{{ route('catalogo.update.vigneto', ':id') }}".replace(':id', id);
            methodInp.value    = 'PUT';

            document.getElementById('vigneto-nome').value           = nome;
            document.getElementById('vigneto-descrizione').value    = descrizione;
            document.getElementById('vigneto-disponibilita').value  = disponibilita;
            document.getElementById('vigneto-prezzo-annuo').value   = prezzoAnnuale;

            // set checkbox
            if (visibileChk) {
                // accetta "1", 1, "true", true
                const isTrue = (visibileAttr === '1' || visibileAttr === 1 || visibileAttr === 'true' || visibileAttr === true);
                visibileChk.checked = isTrue;
            }
        }
    });
});
</script>
