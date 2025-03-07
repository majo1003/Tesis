<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario está autenticado como doctor
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'doctor') {
    // Si no está autenticado, redirigir al login
    header("Location: index.php");
    exit;
}

$id_doctor = $_SESSION['id_doctor'];

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script type="module" src="script/implementarAPIGoogleMaps.js"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAbEJN9YwUlDW1TrU2VokmDJBW5-dWQK5E&callback=initMap&v=weekly"
        defer></script>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/styles-crud.css">
    <link rel="stylesheet" href="css/styles-profile.css">
    <title>Home</title>
</head>

<body>

    <header class="header-pantalla">
        <div class="titulo">
            <h1>Bienvenido Administrador</h1>
        </div>

        <div class="cerrar-sesion">
        <a href="BD/cerrarSesion.php" class="dr-buttonHeader">Cerrar Sesión</a>
        </div>
        
        <div class="cerrar-sesion">
            <button onclick="location.href='profilePaciente.html'">Profile</button>
        </div>

    </header>



    <main class="pantalla-principal">
        <header id="menu-lateral">
            <div class="manu">
                <nav>
                    <ul>
                        <li><button class="nav-link" data-target="calendar" href="#"> <img
                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAALNJREFUSEvdlUEKgzAQRZ/nEATpot7Gy3TTG7QX6kHcuBMF79FSMBQi099Mq1izCzPz//yfSZKx8MoWxkcR3KcGrDwVX5+gBi5A5bSuBU7ALdTH0kcgd4KHsg44WATB0y85XtbHClYjUNNlKZxNlaVgPwSx5NT97KKlAqh8SZA6rts7ZGWBikuLFICKS4L/P4OfKxiAIhU1yu+B0nqunx/OFTg6SRrg/O7DceLaZd5X8+NGHks5NBnCpFrAAAAAAElFTkSuQmCC" />
                                Calendario</button>
                        </li>
                        <li><button href="#" class="nav-link" data-target="clientes"><img
                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAARlJREFUSEu11c8qxUEUwPHPtfAGFkpKKcXCU3gA5R1sJJEssMFNSSkLGwtr5Q28hA1lx4IsPIHyb+p3pXF/9878zG/WZ77fOXPOzOloeXVa5osFSzjATEPxPXZw1dsfC24x2xDe23aHuTrB5z/hf7hxBoME+zirCCvYHnCYH26qYB0nETAIQr36rWzBGF4j0jieSwkC7KVNwRaOIsEu9kplEDhdnGIUq9gsWeTc7s0uchA84qEyTWGyVAaXuMA1PiroCBawjMU+oqQM3r5bMzyo8yH3s4HjKCZJED6tUNiUFR7h2q/AJMEEnlLomMdNriBnVoSavOcKEg9f5i/KldXWoPWBE0bmIaZzj1zFDx2ZDbn123I6pZH8C+MWNBk3lDwfAAAAAElFTkSuQmCC" />
                                Clientes</button>
                        </li>
                        <li><button href="#" class="nav-link" data-target="tasks"><img
                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAARVJREFUSEvl1TtKBEEQxvHfgpGhkeAbBU08gqIn8ABew8TUSI/hJYzXF8aamPhEAyNjE1EKZmTopXfW3l0TOxu66vt/1VNFdYz5dMasrw1wis0WE11s52JSwA4OsVZY2S32cFLnp4BXzBSK12kPWM4BvqqLGlx/tzHT+B/jaQV/Dmhznt6nBnu6qCfgl4SRAqbwnhgYGSA67QzHOGhAigFNtyF+gUXcYR0fFaQIEA53GxN9iYVKfANvw1QwiWus4BETmMM9thCD2TxFFUzjqnqSEHtCOE/F464IEImzOK+s5sSHAkRy/NRPvPSZjeIKBp23fwB4xvyg75GJiy5byu2D2GhHWC2E3GC/30Yr1M2ntS39oYHfAk1CGRiOJxUAAAAASUVORK5CYII=" />
                                Tasks</button>
                        </li>
                    </ul>
                </nav>
            </div>
        </header>

        <section id="calendar">
            <div>
                <h2>Calendario</h2>
                <div id="calendario"></div>
            </div>
        </section>

        <section id="clientes" class="dr-gap20">
            <div class="lista-paciente">
                <div class="titulo">
                    <h1 class="dr-h1">Lista de pacientes</h1>
                    <span class="dr-span">45 pacientes encontrados</span>
                </div>
                <div id="modales-container"></div>
            </div>
            <div class="buscar-paciente dr-gap20">
                <label for="buscar">Nombre: </label>
                <input type="text" name="buscar">
                <button class="dr-button">Buscar</button>
            </div>
            <div class="tabla-pacientes">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>ID</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th> <!-- Nueva columna para el botón -->
                        </tr>
                    </thead>
                    <tbody id="tabla-pacientes-body">
                        <!-- Aquí se llenarán los pacientes dinámicamente -->
                    </tbody>
                </table>
            </div>
        </section>

        <section id="tasks" class="dr-gap10">

<!-- Detalles del paciente -->
<div class="detalles-pacientes dr-gap20">
    <div class="titulos dr-gap10">
        <h1 id="nombre-enfermedad" class="dr-h1">Dolor de huesos</h1>
        <span id="paciente-name" class="dr-spanGris">Juan Valladares</span>
        <div class="estado dr-gap10">
            <span id="paciente-status" class="dr-spanGris">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAYVJREFUSEu9ljFLw0AUx/9X4uQguHTTIoqgg5/BwVURHaVcaumig7M4ZBBEFxcdhEAuBDfRwc/gB3BQEApWt4IU55DckyumSEzSu9iaLbl3/9/7v3d5CcOYLzZmfeQCbNveJqJjAIuGSbwwxo48z7tR+3IBnPMnAEuG4kn4sxBieRiAcsR7AO6+1zYBTGfFCSH6yRc5yAJ0LctacV23qzY3m81qFEWPAKppSFnApRBi/6cY5/wCwN6oALdCiK1SAM55vySJteQ+lVkYx/FCEATv6rnjOFan02kDmB3qQBOgdCSAewAfANYAzGg12QCgdXJ/NVkDoEp4FcfxWRAEr4rSaDTmpJSHAHb/WqIeEa37vv+QlX69Xl+tVCqqbJPJupEDItrxff+6qDac8wMA56UAtVptwnGcqAjQarWmwjD8LAXQ6moqSKdEox126VOkxrWU8oQxNm/oIHtcpwGGornhg2n6H4C3vNe+yE3SzLyYgQPbtjeI6NT0E6kNGFXN0zpj/6v4Apsu0RkEP99NAAAAAElFTkSuQmCC" />
                Sin atender
            </span>
            <span id="paciente-time" class="dr-spanGris">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAzRJREFUSEvFlU9oVFcUxr9zkxmVMYG6i4oZ2kULlVQCEQTRrkq1iLR1GiHxvTvJIBIc4kJcqNhX1JVIqamCMXnvXFsocSHBtikKLsTanbS2KCH+SSIu3BSUgVTJzBznwpuQ+fOGWBAvvMV777v3d8653z2X8IYHveH10RCQzWaX5XK5LwF8CuAjAEkAAmAGwF9E9FssFrs8PDw8HxVoJMBxnG1KqXPhopGJisgDAPuMMdfrieoCtNZeKdKvwwmPAYwR0a8icif8tgHADhH5iojW2m8ictAYc7oaUgNYtHiuFF2vMebnsCw1AXqep2ZnZ1MiEgBYISKDxpgzi4UVAK31DgBXAPwnIl3GmLtLMYHjOBuJ6BYRKaXUVt/3fy/PWwAMDAysnJubmwLQRkR7giD4sXpxrbXdYDBzvcwPAPgWwGQ8Hu8ob/yCUGs9AOAsgHvMvL5eWRoBUqlUPJFIWHe1AficmcdtMAuAdDo9ISLbRGS/McaCakYjgBVrrU8COAzgAjPvrQBorZ+UfqxRSr3n+/6j/wPo6+vbXCwWbxLRn0EQdFYD5qwT7MPMLxoBmpqakqOjo7PVmv7+/lWFQuFfAM+Y+Z0KgOu6OSJaqZRq9X0/FwGYDg/edHNz85aRkRGb9cLo6elpjcVizwHkmLm1OoNJAO8D6GDmf+oBHMexJbwFoD1sF5uY+WlZm06nO0XkdmiUD6sBP5UsthvAIWY+FeX/TCazNp/P3wDwrohkjDGjiwBHReQ4gB+Y2akG7ClNugjgfjKZ/MDzvGIUJMxkV6nO35U1nuc1z8zMPASwTkS6jTGXKgCpVGpFIpGwZVoHIM3MvJRTXNZorbMAbJuYamlp6RgaGnpZAbAvrut+QkRXX7dVaK27ROQP2yoAfMzMN8vgmiPvuu4RIjoB4DkR9QZB8EtUJmGz6xaRCwASRJQNguD7xfq67dp13WNE9E0otNYcAzARj8f/np+fjxcKhfVKqZ0AUgBWh21lkJmHqoOJvHBc1/2MiM7b091oL+yFo5TKBEFgnVUzGl6ZWuvlAL4AsN2ej9D/tqNat9wmomvt7e3jnuflo4J4u5f+69g0SvsKBtJEKC5JECkAAAAASUVORK5CYII=" />
                1 hora
            </span>
        </div>
    </div>

    <div class="container-descripcion">
        <div class="imagen-description dr-gap20">
            <div class="imagen">
            <img id="paciente-img" src="" alt="Foto del paciente" height="150px" width="150px">
            </div>
            <div class="descripcion">
                <h2>Descripción</h2>
                <p id="paciente-description" class=""></p>
            </div>
        </div>

        <!-- Problemas y Archivos subidos -->
        <div class="problemas">
            <h2 class="dr-h1">Problemas</h2>
            <div id="paciente-problems"></div>
        </div>
        
    </div>

    <!-- Mapa del paciente -->
    <div class="ubicacion-paciente">
        <h1>Localización del paciente</h1>
        <div id="map"></div>
    </div>
</div>

<!-- Ingreso de datos -->
<div class="ingreso-datos">
    <div class="detalles-paciente">
        <h2 class="dr-h1">Detalles del paciente</h2>
        <p>Nombre : <span id="paciente-name2"></span></p>
        <p>Edad: <span id="paciente-age"></span></p>
        <p>Ubicación: <span id="paciente-location"></span></p>
    </div>

    <div class="file-stack">
    <h2 class="dr-h1">Datos</h2>
    <p>Última modificación: 2024 Julio 14</p>
    <p>Documento: </p>
    
    <!-- Formulario para enviar los datos -->
    <form id="uploadForm" enctype="multipart/form-data">
        <!-- Campos ocultos para enviar id_doctor e id_cita -->
        <input type="hidden" id="idDoctor" value="<?php echo $_SESSION['id_doctor']; ?>">        
        <div class="upload-area" id="uploadArea">
            <p>Arrastra y suelta tu archivo aquí o</p>
            <label for="fileInput" class="upload-label">
                Seleccionar archivo
                <input type="file" id="fileInput" name="file" class="file-input" />
            </label>
        </div>

        <button type="submit" id="submitButton" class="dr-button">Enviar</button>
        <p id="fileName" class="file-name"></p>
    </form>
</div>

</div>
</section>
    </main>

    <footer>
        <p>&copy; 2024 Maria Jose Encalada. Todos los derechos reservados.</p>
    </footer>

    <script>
            function mostrarUbicacionPaciente(idPaciente) {
        fetch(`BD/getPacienteUbicacion.php?id=${idPaciente}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error("Error en la carga de datos:", data.error);
                    alert('No se pudo cargar la ubicación del paciente.');
                    return;
                }

                // Muestra la ubicación del paciente
                const pacienteLocation = data.ubicacion;

                if (pacienteLocation) {
                    // Mostrar los datos de ubicación en el mapa
                    mostrarMapa(pacienteLocation);
                } else {
                    alert('Ubicación no disponible para este paciente');
                }
            })
            .catch(error => {
                console.error('Error al obtener los datos del paciente:', error);
                alert('Hubo un problema al obtener los datos.');
            });
    }

    // Llamada inicial para obtener los archivos cuando se carga la página
    document.addEventListener('DOMContentLoaded', function() {
        const idCita = document.getElementById('idCita').value; // Obtener el ID de la cita desde el input
        if (idCita) {
            obtenerArchivos(idCita); // Obtener los archivos para esta cita al cargar la página
        }
    });
    </script>


<script>
            // Espera a que todo el DOM esté completamente cargado
            document.addEventListener('DOMContentLoaded', function() {
            const element = document.getElementById("paciente-name");

            if (element) {
                // Si el elemento existe, se actualiza su texto
                element.textContent = "Nuevo valor";
            } else {
                // Si el elemento no existe, muestra un mensaje de error
                console.log("El elemento no existe");
            }
        });
</script>

    <script>
        document.addEventListener("DOMContentLoaded", obtenerPacientes);

        function obtenerPacientes() {
            fetch('BD/getCitas.php')
                .then(response => response.text())
                .then(data => {
                    console.log('Respuesta completa:', data);

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
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${paciente.paciente}</td>
                    <td>${paciente.id_paciente}</td>
                    <td>${paciente.descripcion}</td>
                    <td>${paciente.atendida}</td>
                    <td>
                        <button 
                            data-id="${paciente.id_paciente}" 
                            data-id-cita="${paciente.id_cita}" 
                            class="nav-link" 
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



    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: 'https://fullcalendar.io/api/demo-feeds/events.json'
            });
            calendar.render();
        });
    </script>

    <script src="script/secciones.js"></script>
    <script src="script/ingresarDocumento.js"></script>
    <!-- <script src="script/getpacientes.js"></script> -->

</body>

</html>

