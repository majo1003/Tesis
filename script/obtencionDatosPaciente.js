document.addEventListener("DOMContentLoaded", obtenerPacientes);

function obtenerPacientes() {
    fetch('BD/getCitas.php')
        .then(response => response.text())
        .then(data => {

            try {
                const pacientes = JSON.parse(data);

                if (pacientes.error) {
                    console.error('Error en el servidor:', pacientes.error);
                    alert('Hubo un error al cargar los pacientes: ' + pacientes.error);
                    return;
                }

                mostrarPacientes(pacientes);
            } catch (e) {
                console.error('Error al parsear JSON:', e);
                alert('Hubo un problema al cargar los pacientes.');
            }
        })
        .catch(error => {
            console.error('Error al cargar los pacientes:', error);
            alert('Hubo un problema al cargar los pacientes.');
        });
}

function mostrarPacientes(pacientes) {
    const tablaPacientesBody = document.getElementById('tabla-pacientes-body');

    tablaPacientesBody.innerHTML = '';

    pacientes.forEach(paciente => {
        // Determina la clase en función del estado de la cita
        let claseEstado = '';
        if (paciente.atendida === 'Atendido') {
            claseEstado = 'estado-atendida'; // Verde
        } else if (paciente.atendida === 'No atendido') {
            claseEstado = 'estado-sin-atender'; // Rojo
        }

        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${paciente.paciente}</td>
            <td>${paciente.id_paciente}</td>
            <td>${paciente.tipo_enfermedad}</td>
            <td>${paciente.descripcion}</td>
            <td class="${claseEstado}">${paciente.atendida}</td>
            <td>
                <button 
                    data-id="${paciente.id_paciente}" 
                    data-id-cita="${paciente.id_cita}" 
                    class="nav-link dr-button" 
                    data-target="tasks">
                    Ver tareas
                </button>
            </td>
        `;
        tablaPacientesBody.appendChild(row);
    });

    document.querySelectorAll('.nav-link').forEach(button => {
        button.addEventListener('click', function() {
            const pacienteId = this.getAttribute('data-id');
            const citaId = this.getAttribute('data-id-cita');
            redirigirATareas(pacienteId, citaId);
        });
    });
}



function redirigirATareas(idPaciente, idCita) {
    window.location.hash = `#tasks?id=${idPaciente}&id_cita=${idCita}`;
    obtenerPaciente(idPaciente, idCita);
    getArchivos(idCita);
}

function getArchivos(idCita) {
    console.log("📂 Obteniendo archivos para cita:", idCita);

    $.ajax({
        url: "BD/obtenerArchivos.php",
        type: "POST",
        data: { idCita: idCita },
        dataType: "json",
        success: function(response) {
            console.log("✅ Respuesta del servidor:", response);

            if (Array.isArray(response) && response.length > 0) {
                mostrarArchivos(response);
            } else {
                limpiarListaArchivos();
            }
        },
        error: function(xhr, status, error) {
            console.error("⛔ Error en AJAX:", error);
            console.error("📡 Estado:", status);
            console.error("📜 Respuesta del servidor:", xhr.responseText);
            limpiarListaArchivos();
        }
    });
}

function mostrarArchivos(archivos) {
    const listaArchivos = document.getElementById("paciente-problems");
    listaArchivos.innerHTML = ""; // Limpiar antes de actualizar

    archivos.forEach(archivo => {
        const li = document.createElement("li");
        const enlace = document.createElement("a");

        enlace.href = `BD/descargar_pdf.php?idArchivo=${archivo.id_archivo}`;
        enlace.textContent = archivo.nombre_archivo;
        enlace.target = "_blank";

        li.appendChild(enlace);
        listaArchivos.appendChild(li);
    });
}

// Función para limpiar la lista de archivos cuando no hay archivos
function limpiarListaArchivos() {
    const listaArchivos = document.getElementById("paciente-problems");
    listaArchivos.innerHTML = "<li>No hay archivos disponibles.</li>";
}



function obtenerPaciente(idPaciente, idCita) {
    fetch(`BD/getPacientes.php?id=${idPaciente}&id_cita=${idCita}`)
        .then(response => response.text())
        .then(data => {
            console.log('Respuesta recibida:', data);

            try {
                const jsonData = JSON.parse(data);

                if (jsonData.error) {
                    console.error("Error:", jsonData.error);
                    alert(jsonData.error);
                    return;
                }

                // Actualiza los datos en la página HTML
                if (jsonData.foto) {
                    const fotoElement = document.getElementById('paciente-img');
                    fotoElement.src = `data:image/jpeg;base64,${jsonData.foto}`;  // Usar jsonData.foto aquí
                }

                document.getElementById("nombre-enfermedad").textContent = jsonData.diagnostico || "No especificado";
                document.getElementById("paciente-name").textContent = `${jsonData.nombre || "Desconocido"}`;
                document.getElementById("paciente-name2").textContent = `${jsonData.nombre || "Desconocido"}`;
                document.getElementById("paciente-age").textContent = jsonData.edad || "No disponible";  // Edad del paciente
                document.getElementById("paciente-location").textContent = jsonData.ubicacion || "No especificada";  // Ubicación del paciente
                document.getElementById("paciente-status").textContent = jsonData.atendida === "Atendida" ? "Atendida" : "Sin atender";
                document.getElementById("paciente-description").textContent = jsonData.descripcion || "Desconocido";
                document.getElementById("hora-inicio").textContent = jsonData.hora_inicio || "No disponible";
                document.getElementById("hora-fin").textContent = jsonData.hora_fin || "No disponible";

            } catch (e) {
                console.error("Error al parsear JSON:", e);
            }
        })
        .catch(error => {
            console.error("Error en la solicitud:", error);
            alert('Hubo un problema al obtener los datos del paciente.');
        });
}


