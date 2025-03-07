// Función para cargar los pacientes desde la base de datos
async function cargarPacientes() {
    try {
        // Hacemos la solicitud a getPacientes.php para obtener los datos en formato JSON
        const response = await fetch('getPacientes.php');
        const pacientes = await response.json(); // Obtenemos los pacientes como JSON

        // Obtenemos el tbody de la tabla donde vamos a insertar las filas
        const tbody = document.getElementById('tabla-pacientes-body');

        // Limpiamos cualquier contenido previo
        tbody.innerHTML = '';

        // Recorremos los pacientes y agregamos cada uno como fila en la tabla
        pacientes.forEach(paciente => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <div class="nombre">
                        <span>${paciente.nombre}</span>
                        <span class="nivel">${paciente.nivel}</span>
                    </div>
                </td>
                <td>${paciente.id}</td>
                <td>${paciente.descripcion}</td>
                <td class="estado-celda">
                    <div class="estado">${paciente.estado}</div>
                </td>
            `;
            tbody.appendChild(tr); // Agregamos la fila a la tabla
        });

    } catch (error) {
        console.error("Error al cargar los pacientes:", error);
    }
}

// Llamamos a la función cargarPacientes cuando la página esté cargada
document.addEventListener('DOMContentLoaded', cargarPacientes);
