<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('vignetoModal');
    if (!modal) return;

    const form = document.getElementById('vignetoForm');
    const method = document.getElementById('vignetoFormMethod');
    const title = document.getElementById('vignetoModalLabel');
    const submit = document.getElementById('vigneto-submit-btn');

    modal.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        const mode = btn.dataset.mode;

        if (mode === "create") {
            title.textContent = "Nuovo vigneto";
            submit.textContent = "Crea";

            form.action = "";
            method.value = "POST";

            document.getElementById('vigneto-nome').value = "";
            document.getElementById('vigneto-descrizione').value = "";
            document.getElementById('vigneto-disponibilita').value = "";
            document.getElementById('vigneto-prezzo').value = "";
        }

        if (mode === "edit") {
            title.textContent = "Modifica vigneto";
            submit.textContent = "Salva modifiche";

            const id = btn.dataset.id;

            form.action = "".replace(":id", id);
            method.value = "PUT";

            document.getElementById('vigneto-nome').value          = btn.dataset.nome;
            document.getElementById('vigneto-descrizione').value   = btn.dataset.descrizione;
            document.getElementById('vigneto-disponibilita').value = btn.dataset.disponibilita;
            document.getElementById('vigneto-prezzo').value        = btn.dataset.prezzo;
        }
    });
});
</script>
