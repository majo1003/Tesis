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
        const atendidaTexto = paciente.atendida;
        const atendidaValor = atendidaTexto === 'Atendido' ? 1 : 0;
        const isChecked = atendidaValor === 1 ? 'checked' : '';
        const claseEstado = atendidaValor === 1 ? 'estado-atendida' : 'estado-sin-atender';

        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${paciente.paciente}</td>
            <td>${paciente.id_paciente}</td>
            <td>${paciente.tipo_enfermedad}</td>
            <td>${paciente.descripcion}</td>
            <td class="${claseEstado}">
                <label>
                    <input type="checkbox" 
                        class="checkbox-atendida"
                        data-id-cita="${paciente.id_cita}"
                        ${isChecked}> Atendida
                </label>
                <br>
                <button class="guardar-cambio-btn dr-button mini"
                        data-id-cita="${paciente.id_cita}"
                        disabled>
                    Guardar
                </button>
            </td>
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

    // Activar botón cuando se modifica checkbox
    document.querySelectorAll('.checkbox-atendida').forEach(cb => {
        cb.addEventListener('change', function () {
            const btn = this.closest('td').querySelector('.guardar-cambio-btn');
            btn.disabled = false;
        });
    });

    // Guardar cambio de estado
    document.querySelectorAll('.guardar-cambio-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const idCita = this.getAttribute('data-id-cita');
            const checkbox = this.closest('td').querySelector('.checkbox-atendida');
            const atendida = checkbox.checked ? 1 : 0;
            const botonGuardar = this;

            const formData = new FormData();
            formData.append('id_cita', idCita);
            formData.append('atendida', atendida);

            fetch('BD/actualizar_estado_cita.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const celda = botonGuardar.closest('td');
                    celda.className = atendida === 1 ? 'estado-atendida' : 'estado-sin-atender';
                    botonGuardar.disabled = true;
                    alert('Estado actualizado correctamente');
                } else {
                    alert('Error al actualizar el estado: ' + (data.error || 'Desconocido'));
                }
            })
            .catch(err => {
                console.error('Fetch error:', err);
                alert('No se pudo conectar al servidor.');
            });
        });
    });

    // Navegar a tareas
    document.querySelectorAll('.nav-link').forEach(button => {
        button.addEventListener('click', function () {
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

