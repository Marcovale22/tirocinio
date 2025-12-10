<script>
document.addEventListener('DOMContentLoaded', function () {
    const vinoModal = document.getElementById('vinoModal');
    const vinoForm  = document.getElementById('vinoForm');
    const methodInp = document.getElementById('vinoFormMethod');
    const titleEl   = document.getElementById('vinoModalLabel');
    const submitBtn = document.getElementById('vino-submit-btn');

    if (!vinoModal) return;

    vinoModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button) return;

        const mode = button.getAttribute('data-mode'); // create | edit

        if (mode === 'create') {
            // Modal in modalità AGGIUNTA
            titleEl.textContent   = 'Nuovo vino';
            submitBtn.textContent = 'Crea';

            // action = route store
            vinoForm.action = "{{ route('catalogo.store.vini') }}";
            methodInp.value = 'POST';

            // pulisco i campi
            ['vino-nome','vino-prezzo','vino-annata','vino-formato',
             'vino-gradazione','vino-disponibilita','vino-solfiti'
            ].forEach(function(id) {
                const input = document.getElementById(id);
                if (input) input.value = '';
            });
        }

        if (mode === 'edit') {
            // Modal in modalità MODIFICA
            titleEl.textContent   = 'Modifica vino';
            submitBtn.textContent = 'Salva modifiche';

            const id            = button.getAttribute('data-id');
            const nome          = button.getAttribute('data-nome') || '';
            const prezzo        = button.getAttribute('data-prezzo') || '';
            const annata        = button.getAttribute('data-annata') || '';
            const formato       = button.getAttribute('data-formato') || '';
            const gradazione    = button.getAttribute('data-gradazione') || '';
            const disponibilita = button.getAttribute('data-disponibilita') || '';
            const solfiti       = button.getAttribute('data-solfiti') || '';

            // action = route update con id
            vinoForm.action = "{{ route('catalogo.update.vini', ':id') }}".replace(':id', id);
            methodInp.value = 'PUT';

            // riempio i campi
            document.getElementById('vino-nome').value           = nome;
            document.getElementById('vino-prezzo').value         = prezzo;
            document.getElementById('vino-annata').value         = annata;
            document.getElementById('vino-formato').value        = formato;
            document.getElementById('vino-gradazione').value     = gradazione;
            const dispInput = document.getElementById('vino-disponibilita');
            const solfitiInput = document.getElementById('vino-solfiti');
            if (dispInput)    dispInput.value    = disponibilita;
            if (solfitiInput) solfitiInput.value = solfiti;
        }
    });
});
</script>
