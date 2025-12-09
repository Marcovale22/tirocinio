<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('merchModal');
    if (!modal) return;

    const form      = document.getElementById('merchForm');
    const methodInp = document.getElementById('merchFormMethod');
    const titleEl   = document.getElementById('merchModalLabel');
    const submitBtn = document.getElementById('merch-submit-btn');

    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const mode   = button.getAttribute('data-mode');

        if (mode === 'create') {
            titleEl.textContent   = "Nuovo prodotto merch";
            submitBtn.textContent = "Crea";

            form.action  = "";
            methodInp.value = "POST";

            document.getElementById('merch-nome').value     = '';
            document.getElementById('merch-prezzo').value   = '';
            document.getElementById('merch-immagine').value = '';
        }

        if (mode === 'edit') {
            titleEl.textContent   = "Modifica prodotto merch";
            submitBtn.textContent = "Salva modifiche";

            const id       = button.dataset.id;
            const nome     = button.dataset.nome;
            const prezzo   = button.dataset.prezzo;
            const immagine = button.dataset.immagine;

            form.action = "".replace(":id", id);
            methodInp.value = "PUT";

            document.getElementById('merch-nome').value     = nome ?? '';
            document.getElementById('merch-prezzo').value   = prezzo ?? '';
            document.getElementById('merch-immagine').value = immagine ?? '';
        }
    });
});
</script>
