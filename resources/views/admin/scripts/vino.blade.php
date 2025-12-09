<script>
document.addEventListener('DOMContentLoaded', function () {
    var vinoModal = document.getElementById('vinoModal');
    var vinoForm  = document.getElementById('vinoForm');
    var methodInp = document.getElementById('vinoFormMethod');
    var titleEl   = document.getElementById('vinoModalLabel');
    var submitBtn = document.getElementById('vino-submit-btn');

    vinoModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var mode   = button.getAttribute('data-mode'); // create | edit

        if (mode === 'create') {
            // Modal in modalità AGGIUNTA
            titleEl.textContent = 'Nuovo vino';
            submitBtn.textContent = 'Crea';

            vinoForm.action = "";
            methodInp.value = 'POST';

            // pulisco i campi
            document.getElementById('vino-nome').value       = '';
            document.getElementById('vino-prezzo').value     = '';
            document.getElementById('vino-annata').value     = '';
            document.getElementById('vino-formato').value    = '';
            document.getElementById('vino-gradazione').value = '';
        }

        if (mode === 'edit') {
            // Modal in modalità MODIFICA
            titleEl.textContent = 'Modifica vino';
            submitBtn.textContent = 'Salva modifiche';

            var id        = button.getAttribute('data-id');
            var nome      = button.getAttribute('data-nome');
            var prezzo    = button.getAttribute('data-prezzo');
            var annata    = button.getAttribute('data-annata');
            var formato   = button.getAttribute('data-formato');
            var gradazione= button.getAttribute('data-gradazione');

            vinoForm.action = "".replace(':id', id);
            methodInp.value = 'PUT';

            document.getElementById('vino-nome').value       = nome ?? '';
            document.getElementById('vino-prezzo').value     = prezzo ?? '';
            document.getElementById('vino-annata').value     = annata ?? '';
            document.getElementById('vino-formato').value    = formato ?? '';
            document.getElementById('vino-gradazione').value = gradazione ?? '';
        }
    });
});
</script>