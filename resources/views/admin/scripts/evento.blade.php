<script>
document.addEventListener('DOMContentLoaded', function () {
    const eventoModal = document.getElementById('eventoModal');
    const eventoForm  = document.getElementById('eventoForm');
    const methodInp   = document.getElementById('eventoFormMethod');
    const titleEl     = document.getElementById('eventoModalLabel');
    const submitBtn   = document.getElementById('evento-submit-btn');

    if (!eventoModal) return;

    eventoModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        // Se il modal è aperto da JS (dopo errore) non c'è un bottone → non tocco i campi
        if (!button) return;

        const mode = button.getAttribute('data-mode'); // create | edit

        if (mode === 'create') {
            titleEl.textContent   = 'Nuovo evento';
            submitBtn.textContent = 'Crea';

            eventoForm.action = "{{ route('catalogo.store.evento') }}";
            methodInp.value   = 'POST';

            // pulisco i campi
            ['evento-nome','evento-prezzo','evento-disponibilita',
             'evento-data','evento-ora','evento-luogo','evento-descrizione'
            ].forEach(function(id) {
                const input = document.getElementById(id);
                if (!input) return;
                if (input.tagName.toLowerCase() === 'textarea') {
                    input.value = '';
                } else {
                    input.value = '';
                }
            });
        }

        if (mode === 'edit') {
            titleEl.textContent   = 'Modifica evento';
            submitBtn.textContent = 'Salva modifiche';

            const id            = button.getAttribute('data-id');
            const nome          = button.getAttribute('data-nome') || '';
            const prezzo        = button.getAttribute('data-prezzo') || '';
            const disponibilita = button.getAttribute('data-disponibilita') || '';
            const dataEvento    = button.getAttribute('data-data-evento') || '';
            const oraEvento     = button.getAttribute('data-ora-evento') || '';
            const luogo         = button.getAttribute('data-luogo') || '';
            const descrizione   = button.getAttribute('data-descrizione') || '';

            eventoForm.action = "{{ route('catalogo.update.evento', ':id') }}".replace(':id', id);
            methodInp.value   = 'PUT';

            document.getElementById('evento-nome').value           = nome;
            document.getElementById('evento-prezzo').value         = prezzo;
            document.getElementById('evento-disponibilita').value  = disponibilita;
            document.getElementById('evento-data').value           = dataEvento;
            document.getElementById('evento-ora').value            = oraEvento;
            document.getElementById('evento-luogo').value          = luogo;
            const descInput = document.getElementById('evento-descrizione');
            if (descInput) descInput.value = descrizione;
        }
    });
});
</script>
