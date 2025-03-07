document.addEventListener("DOMContentLoaded", () => {
    const uploadArea = document.getElementById("uploadArea");
    const fileInput = document.getElementById("fileInput");
    const fileNameDisplay = document.getElementById("fileName");
    const submitButton = document.getElementById("submitButton");

    // Manejador para arrastrar y soltar
    uploadArea.addEventListener("dragover", (event) => {
        event.preventDefault();
        uploadArea.classList.add("dragover");
    });

    uploadArea.addEventListener("dragleave", () => {
        uploadArea.classList.remove("dragover");
    });

    uploadArea.addEventListener("drop", (event) => {
        event.preventDefault();
        uploadArea.classList.remove("dragover");

        const file = event.dataTransfer.files[0];
        if (file) {
            fileInput.files = event.dataTransfer.files; // Asignar archivo al input
            fileNameDisplay.textContent = `Archivo seleccionado: ${file.name}`;
        }
    });

    // Manejador para selección manual
    fileInput.addEventListener("change", () => {
        const file = fileInput.files[0];
        if (file) {
            fileNameDisplay.textContent = `Archivo seleccionado: ${file.name}`;
        }
    });

    // Manejador para enviar el archivo
    submitButton.addEventListener("click", () => {
        if (fileInput.files.length === 0) {
            alert("Por favor, selecciona un archivo antes de enviar.");
        } else {
            const file = fileInput.files[0];

            // Obtener el ID de la cita
            const citaId = document.querySelector('[data-id-cita]').getAttribute('data-id-cita');

            const formData = new FormData();
            formData.append("file", file);
            formData.append("id_cita", citaId);

            // Mostrar los datos que se están enviando
            console.log("Enviando datos:", {
                citaId: citaId,
                file: file.name
            });

            // Realizar la solicitud POST
            fetch('upload.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => {
                console.log("Respuesta del servidor:", response);  // Depuración: Mostrar la respuesta del servidor
                return response.text(); // Cambiar a text() para ver si el servidor responde con HTML o JSON
            })
            .then(text => {
                console.log("Texto recibido:", text);
                try {
                    const data = JSON.parse(text);
                    if (data.success) {
                        alert("Archivo subido correctamente.");
                    } else {
                        alert("Hubo un error al subir el archivo: " + (data.error || "Desconocido"));
                    }
                } catch (e) {
                    console.error("Error al analizar el JSON:", e);
                    alert("Error en el formato de la respuesta.");
                }
            })
            .catch(error => {
                console.error("Error al subir el archivo:", error);
                alert("Hubo un problema al subir el archivo.");
            });
        }
    });
});
