<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('eventoModal');
    if (!modal) return;

    const form = document.getElementById('eventoForm');
    const method = document.getElementById('eventoFormMethod');
    const title = document.getElementById('eventoModalLabel');
    const submit = document.getElementById('evento-submit-btn');

    modal.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        const mode = btn.dataset.mode;

        if (mode === "create") {
            title.textContent = "Nuovo evento";
            submit.textContent = "Crea";

            form.action = "";
            method.value = "POST";

            document.getElementById('evento-nome').value = "";
            document.getElementById('evento-prezzo').value = "";
            document.getElementById('evento-data').value = "";
            document.getElementById('evento-ora').value = "";
            document.getElementById('evento-disponibilita').value = "";
        }

        if (mode === "edit") {
            title.textContent = "Modifica evento";
            submit.textContent = "Salva modifiche";

            const id = btn.dataset.id;

            form.action = "".replace(":id", id);
            method.value = "PUT";

            document.getElementById('evento-nome').value          = btn.dataset.nome;
            document.getElementById('evento-prezzo').value        = btn.dataset.prezzo;
            document.getElementById('evento-data').value          = btn.dataset.data;
            document.getElementById('evento-ora').value           = btn.dataset.ora;
            document.getElementById('evento-disponibilita').value = btn.dataset.disponibilita;
        }
    });
});
</script>
