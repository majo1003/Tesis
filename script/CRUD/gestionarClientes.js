document.addEventListener("DOMContentLoaded", function () {
    const modalCliente = new bootstrap.Modal(document.getElementById("modalCliente"));
    const modalTitle = document.getElementById("modalClienteLabel");
    const formCliente = document.getElementById("formCliente");

    // Botones
    const btnAgregarCliente = document.getElementById("btnAgregarCliente");
    const btnEditarCliente = document.getElementById("btnEditarCliente");

    // Evento para abrir modal en modo "Agregar"
    btnAgregarCliente.addEventListener("click", () => {
        modalTitle.textContent = "Agregar Cliente";
        formCliente.reset();
        modalCliente.show();
    });

    // Evento para abrir modal en modo "Editar"
    btnEditarCliente.addEventListener("click", () => {
        modalTitle.textContent = "Editar Cliente";
        // Simula la carga de datos existentes
        document.getElementById("nombreCliente").value = "Juan Valladares";
        document.getElementById("idCliente").value = "1234";
        document.getElementById("descripcionCliente").value = "Dolor de huesos";
        modalCliente.show();
    });

    // Evento para el formulario
    formCliente.addEventListener("submit", (e) => {
        e.preventDefault();
        const nombre = document.getElementById("nombreCliente").value;
        const id = document.getElementById("idCliente").value;
        const descripcion = document.getElementById("descripcionCliente").value;

        console.log("Datos del cliente:");
        console.log("Nombre:", nombre);
        console.log("ID:", id);
        console.log("Descripción:", descripcion);

        alert("Cliente guardado exitosamente.");
        modalCliente.hide();
    });
});
